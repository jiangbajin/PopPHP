<?php
/**
 * Pop PHP Framework
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.TXT.
 * It is also available through the world-wide-web at this URL:
 * http://www.popphp.org/LICENSE.TXT
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to info@popphp.org so we can send you a copy immediately.
 *
 * @category   Pop
 * @package    Pop_Cache
 * @author     Nick Sagona, III <nick@popphp.org>
 * @copyright  Copyright (c) 2009-2012 Moc 10 Media, LLC. (http://www.moc10media.com)
 * @license    http://www.popphp.org/LICENSE.TXT     New BSD License
 */

/**
 * @namespace
 */
namespace Pop\Cache;

use Pop\Locale\Locale;

/**
 * @category   Pop
 * @package    Pop_Cache
 * @author     Nick Sagona, III <nick@popphp.org>
 * @copyright  Copyright (c) 2009-2012 Moc 10 Media, LLC. (http://www.moc10media.com)
 * @license    http://www.popphp.org/LICENSE.TXT     New BSD License
 * @version    0.9
 */
class Memcached implements CacheInterface
{

    /**
     * Memcache object
     * @var Memcache
     */
    protected $_memcache = null;

    /**
     * Memcache version
     * @var string
     */
    protected $_version = null;
    /**
     * Constructor
     *
     * Instantiate the memcache cache object
     *
     * @throws Exception
     * @return void
     */
    public function __construct()
    {
        if (!class_exists('Memcache')) {
            throw new Exception(Locale::factory()->__('Error: Memcache is not available on this server.'));
        } else {
            $this->_memcache = new \Memcache();
            if (!$this->_memcache->connect('localhost', 11211)) {
                throw new Exception(Locale::factory()->__('Error: Unable to connect to the memcached server.'));
            } else {
                $this->_version = $this->_memcache->getVersion();
            }
        }
    }

    /**
     * Method to get the current version of memcache.
     *
     * @return string
     */
    public function getVersion()
    {
        return $this->_version;
    }

    /**
     * Method to save a value to cache.
     *
     * @param  string $id
     * @param  mixed  $value
     * @param  string $time
     * @return void
     */
    public function save($id, $value, $time = null)
    {
        $time = (null === $time) ? time() : time() + $time;
        $this->_memcache->set($id, $value, false, $time);
    }

    /**
     * Method to load a value from cache.
     *
     * @param  string $id
     * @param  string $time
     * @return mixed
     */
    public function load($id, $time = null)
    {
        return $this->_memcache->get($id);
    }

    /**
     * Method to delete a value in cache.
     *
     * @param  string $id
     * @return void
     */
    public function remove($id)
    {
        $this->_memcache->delete($id);
    }

    /**
     * Method to clear all stored values from cache.
     *
     * @return void
     */
    public function clear()
    {
        $this->_memcache->flush();
    }

}