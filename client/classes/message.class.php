<?php 

class Message {

    public function __construct() {
        if(!isset($_SESSION['message']))
            $_SESSION['message'] = [];
    }

    /**
     * Set Message For form
     *
     * @param mixed $message
     * @return void
     */
    public function setMessage($message)
    {
        $_SESSION['message'][] = $message;
    }

    /**
     * get flash mesage
     *
     * @return mixed
     */
    public function getMessage() {
        $message = NULL;
        try
        {
            if(count($_SESSION['message']) > 0)
                $message = $_SESSION['message'];
        }
        catch(Exception $e)
        {
            echo 'error: '.$e->__toString();
            die(0);
        }
        finally 
        {
            unset($_SESSION['message']);
        }

        return $message;
    }
}

?>