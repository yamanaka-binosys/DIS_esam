<?php if ( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );
class MY_Session extends CI_Session {
  function __construct ()
  {
    parent :: __construct();
  }
//途中略（function _serialize()とかfunction _unserialize()とかのbugfix等々が間にある）
  // --------------------------------------------------------------------

  /**
   * Write the session cookie
   *
   * @access  public
   * @return  void
   */
  function _set_cookie($cookie_data = NULL)
  {
    if (is_null($cookie_data))
    {
      $cookie_data = $this->userdata;
    }

    // Serialize the userdata for the cookie
    $cookie_data = $this->_serialize($cookie_data);

    //add start here
    //see:http://codeigniter.com/bug_tracker/bug/7358/
    $cookie_md5 = md5($cookie_data);
    static $last_cookie_md5='';
    if($last_cookie_md5 !== $cookie_md5){
      $last_cookie_md5 = $cookie_md5;
    }else{
      return;
    }
    //add end

    if ($this->sess_encrypt_cookie == TRUE)
    {
      $cookie_data = $this->CI->encrypt->encode($cookie_data);
      #debug
      #$hoge=$this->CI->encrypt->decode($cookie_data);
      #var_dump(md5($hoge)) ;
    }
    else
    {
      // if encryption is not used, we provide an md5 hash to prevent userside tampering
      $cookie_data = $cookie_data.md5($cookie_data.$this->encryption_key);
    }

    // Set the cookie
    setcookie(
          $this->sess_cookie_name,
          $cookie_data,
          $this->sess_expiration + time(),
          $this->cookie_path,
          $this->cookie_domain,
          0
        );
  }

//下略

}//endofclass
/**
 * End of file MY_Session.php
 */
/**
 * Location: ./application/libraries/MY_Session.php
 */

?>