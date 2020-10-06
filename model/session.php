<?php
class Session
{
    private $sessionName;

    public function __construct($sessionName=null, $regenerateId=false, $sessionId=null)
    {
        if (!is_null($sessionId)) {
            session_id($sessionId);
        }
        session_start();

        if ($regenerateId) {
            session_regenerate_id();
			$this->set('session_expire',time()+3600); // 1 hour session lifetime
        }

        if (!is_null($sessionName)) {
            $this->sessionName = session_name($sessionName);
        }

		if (!$this->get('session_expire') || $this->get('session_expire')<time() )
		{
//			if ($this->get('userdata')) $this->delete('userdata');
//			$this->regenerateId();
//			$this->set('session_expire',time()+3600);
		}
    }


    public function set($key, $val)
    {
        $_SESSION[$key] = $val;
    }

    /*
        to set something like $_SESSION['key1']['key2']['key3']:
        $session->setMd(array('key1', 'key2', 'key3'), 'value')
    */
    public function setMd($keyArray, $val)
    {
        $arrStr = "['".implode("']['", $keyArray)."']";
        $_SESSION{$arrStr} = $val;
    }


    public function get($key)
    {
        return (isset($_SESSION[$key])) ? $_SESSION[$key] : false;
    }

    /*
        to get something like $_SESSION['key1']['key2']['key3']:
        $session->getMd(array('key1', 'key2', 'key3'))
    */
    public function getMd($keyArray)
    {
        $arrStr = "['".implode("']['", $keyArray)."']";
        return (isset($_SESSION{$arrStr})) ? $_SESSION{$arrStr} : false;
    }

    public function getUserData($key = "id")
    {
		return (isset($_SESSION['userdata'][$key])) ? $_SESSION['userdata'][$key] : false;
    }

    public function delete($key)
    {
        if (isset($_SESSION[$key])) {
            unset($_SESSION[$key]);
            return true;
        }
        return false;
    }


    public function deleteMd($keyArray)
    {
        $arrStr = "['".implode("']['", $keyArray)."']";
        if (isset($_SESSION{$arrStr})) {
            unset($_SESSION{$arrStr});
            return true;
        }
        return false;
    }


    public function regenerateId($destroyOldSession=false)
    {
        session_regenerate_id(false);

        if ($destroyOldSession) {
            //  hang on to the new session id and name
            $sid = session_id();
            //  close the old and new sessions
            session_write_close();
            //  re-open the new session
            session_id($sid);
            session_start();
//			$this->set('session_expire',time()+3600);
        }
    }


    public function destroy()
    {
        return session_destroy();
    }


    public function getName()
    {
        return $this->sessionName;
    }

}