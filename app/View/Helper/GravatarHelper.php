<?php
/**
 * Gravatar Helper
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled with this
 * package in the file LICENSE. It is also available through the world-wide-web
 * at this URL: http://www.opensource.org/licenses/bsd-license
 *
 * @category   Helpers
 * @package    CakePHP
 * @subpackage PHP
 * @copyright  Copyright (c) 2011 Signified (http://signified.com.au)
 * @license    http://www.opensource.org/licenses/bsd-license    New BSD License
 * @version    1.0
 */

/**
 * GravatarHelper class
 *
 * Gravatar Helper class for easy display of Gravatars
 *
 * @category   Helpers
 * @package    CakePHP
 * @subpackage PHP
 * @copyright  Copyright (c) 2011 Signified (http://signified.com.au)
 * @license    http://www.opensource.org/licenses/bsd-license    New BSD License
 */
class GravatarHelper extends AppHelper
{

    /**
     * Gravatar URLs
     */
     const GRAVATAR_URL = 'http://www.gravatar.com/avatar/';
     const GRAVATAR_SECURE_URL = 'https://secure.gravatar.com/avatar/';

    /**
     * Helpers used by GravatarHelper
     *
     * @var array
     * @access public
     */
    public $helpers = array('Html');

    /**
     * Create a Gravatar URL
     */
    public function url($email, $options = array(), $secure = false){
        
        $url = self::GRAVATAR_URL;
        if(env('HTTPS') || $secure){
            $url = self::GRAVATAR_SECURE_URL;
        }
        $hash = md5(strtolower(trim($email)));
        $query = '?' . http_build_query($options);

        return $url . $hash . '.jpg' . $query;
    }

    /**
     * Create a Gravatar
     *
     * @param string $email Email address of the user.
     * @param array $options Array of Gravatar options.
     * @param array $attributes Array of HTML attributes.
     * @return string completed img tag
     * @access public
     * @link http://gravatar.com/site/implement/images/
     */
    public function image($email, $options = array(), $attributes = array(), $secure = false) {

        $url = $this->url($email, $options, $secure);

        if (isset($options['s'])) {
            $attributes['height'] = $options['s'];
            $attributes['width'] = $options['s'];
        } elseif (isset($options['size'])) {
            $attributes['height'] = $options['size'];
            $attributes['width'] = $options['size'];
        }

        return $this->Html->image($url, $attributes);
    }
}
