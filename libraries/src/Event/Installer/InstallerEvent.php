<?php

/**
 * Joomla! Content Management System
 *
 * @copyright  (C) 2023 Open Source Matters, Inc. <https://www.joomla.org>
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Joomla\CMS\Event\Installer;

use Joomla\CMS\Event\AbstractImmutableEvent;
use Joomla\CMS\Event\ReshapeArgumentsAware;
use Joomla\CMS\MVC\Model\BaseModel;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

/**
 * Class for Installer events
 *
 * @since  5.0.0
 */
abstract class InstallerEvent extends AbstractImmutableEvent
{
    use ReshapeArgumentsAware;

    /**
     * The argument names, in order expected by legacy plugins.
     *
     * @var array
     *
     * @since  5.0.0
     * @deprecated 5.0 will be removed in 6.0
     */
    protected $legacyArgumentsOrder = ['subject', 'package'];

    /**
     * Constructor.
     *
     * @param   string  $name       The event name.
     * @param   array   $arguments  The event arguments.
     *
     * @throws  \BadMethodCallException
     *
     * @since   5.0.0
     */
    public function __construct($name, array $arguments = [])
    {
        // Reshape the arguments array to preserve b/c with legacy listeners
        // Do not override existing $arguments in place, or it will break references!
        if ($this->legacyArgumentsOrder) {
            parent::__construct($name, $this->reshapeArguments($arguments, $this->legacyArgumentsOrder));
        } else {
            parent::__construct($name, $arguments);
        }

        if (!\array_key_exists('subject', $this->arguments)) {
            throw new \BadMethodCallException("Argument 'subject' of event {$name} is required but has not been provided");
        }

        if (!\array_key_exists('package', $this->arguments)) {
            throw new \BadMethodCallException("Argument 'package' of event {$name} is required but has not been provided");
        }

        // For backward compatibility make sure the package is referenced
        // @todo: Remove in Joomla 6
        // @deprecated: Passing argument by reference is deprecated, and will not work in Joomla 6
        if (key($arguments) === 0) {
            $this->arguments['package'] = &$arguments[1];
        } elseif (\array_key_exists('package', $arguments)) {
            $this->arguments['package'] = &$arguments['package'];
        }
    }

    /**
     * Setter for the subject argument.
     *
     * @param   BaseModel  $value  The value to set
     *
     * @return  BaseModel
     *
     * @since  5.0.0
     */
    protected function onSetSubject(BaseModel $value): BaseModel
    {
        return $value;
    }

    /**
     * Setter for the package argument.
     *
     * @param   ?array  $value  The value to set
     *
     * @return  ?array
     *
     * @since  5.0.0
     */
    protected function onSetPackage(?array $value): ?array
    {
        return $value;
    }

    /**
     * Getter for the model.
     *
     * @return  BaseModel
     *
     * @since  5.0.0
     */
    public function getModel(): BaseModel
    {
        return $this->arguments['subject'];
    }

    /**
     * Getter for the package.
     *
     * @return  ?array
     *
     * @since  5.0.0
     */
    public function getPackage(): ?array
    {
        return $this->arguments['package'] ?? null;
    }

    /**
     * Update the package.
     *
     * @param   ?array  $value  The value to set
     *
     * @return  static
     *
     * @since  5.0.0
     */
    public function updatePackage(?array $value): static
    {
        $this->arguments['package'] = $this->onSetPackage($value);

        return $this;
    }
}
