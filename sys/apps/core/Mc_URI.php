<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Mc_URI extends CI_URI
{
	/**
	 * Set URI String
	 *
	 * @param 	string	$str
	 * @return	void
	 */
	protected function _set_uri_string($str)
	{
		// Filter out control characters and trim slashes
		$this->uri_string = trim(remove_invisible_characters($str, FALSE), '/');
		// If the URI contains only a slash we'll kill it
		$this->uri_string = ($str == '/') ? '' : $str;
        if (defined('IS_ADMIN') ){
             $this->uri_string = 'admin/' . $this->uri_string;
			 $this->uri_string = str_replace("admin/admin", "admin", $this->uri_string);
		}else{
			if(strpos($_SERVER['REQUEST_URI'],'/admin') !== false){
				show_404();
			}
		}

		if ($this->uri_string !== '')
		{
			// Remove the URL suffix, if present
			if (($suffix = (string) $this->config->item('url_suffix')) !== '')
			{
				$slen = strlen($suffix);
				if (substr($this->uri_string, -$slen) === $suffix)
				{
					$this->uri_string = substr($this->uri_string, 0, -$slen);
				}
			}
			$this->segments[0] = NULL;
			// Populate the segments array
			foreach (explode('/', trim($this->uri_string, '/')) as $val)
			{
				$val = trim($val);
				// Filter segments for security
				$this->filter_uri($val);
				if ($val !== '')
				{
					$this->segments[] = $val;
				}
			}
			unset($this->segments[0]);
		}
	}

	/**
	 * Filter URI
	 *
	 * Filters segments for malicious characters.
	 *
	 * @param	string	$str
	 * @return	void
	 */
	public function filter_uri(&$str)
	{
		if ( ! empty($str) && ! empty($this->_permitted_uri_chars) && ! preg_match('/^['.$this->_permitted_uri_chars.']+$/i'.(UTF8_ENABLED ? 'u' : ''), urlencode($str)))
		{
			show_error('The URI you submitted has disallowed characters.', 400);
		}
	}
} // END class MY_URI