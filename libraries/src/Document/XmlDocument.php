<?php

/**
 * Joomla! Content Management System
 *
 * @copyright  (C) 2008 Open Source Matters, Inc. <https://www.joomla.org>
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Joomla\CMS\Document;

use Joomla\CMS\Factory as CmsFactory;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

/**
 * XmlDocument class, provides an easy interface to parse and display XML output
 *
 * @since  1.7.0
 */
class XmlDocument extends Document
{
    /**
     * Document name
     *
     * @var    string
     * @since  3.0.0
     */
    protected $name = 'joomla';

    /**
     * Flag indicating the document should be downloaded (Content-Disposition = attachment) versus displayed inline
     *
     * @var    boolean
     * @since  3.9.0
     */
    protected $isDownload = false;

    /**
     * Class constructor
     *
     * @param   array  $options  Associative array of options
     *
     * @since   1.7.0
     */
    public function __construct($options = [])
    {
        parent::__construct($options);

        // Set mime type
        $this->_mime = 'application/xml';

        // Set document type
        $this->_type = 'xml';
    }

    /**
     * Render the document.
     *
     * @param   boolean  $cache   If true, cache the output
     * @param   array    $params  Associative array of attributes
     *
     * @return  string  The rendered data
     *
     * @since  1.7.0
     */
    public function render($cache = false, $params = [])
    {
        parent::render($cache, $params);

        $disposition = $this->isDownload ? 'attachment' : 'inline';

        CmsFactory::getApplication()->setHeader('Content-disposition', $disposition . '; filename="' . $this->getName() . '.xml"', true);

        return $this->getBuffer();
    }

    /**
     * Returns the document name
     *
     * @return  string
     *
     * @since  1.7.0
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets the document name
     *
     * @param   string  $name  Document name
     *
     * @return  XmlDocument instance of $this to allow chaining
     *
     * @since   1.7.0
     */
    public function setName($name = 'joomla')
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Check if this document is intended for download
     *
     * @return  string
     *
     * @since   3.9.0
     */
    public function isDownload()
    {
        return $this->isDownload;
    }

    /**
     * Sets the document's download state
     *
     * @param   boolean  $download  If true, this document will be downloaded; if false, this document will be displayed inline
     *
     * @return  XmlDocument instance of $this to allow chaining
     *
     * @since   3.9.0
     */
    public function setDownload($download = false)
    {
        $this->isDownload = $download;

        return $this;
    }
}
