<?php

namespace Sinevia;

//============================= START OF CLASS ==============================//
// CLASS: LibreId                                                            //
//===========================================================================//
/**
 * @dateModified 28 Jun 2018
 * @version 2.0
 */
class LibreId {

    private $applicationId = '';

    function __construct($applicationId) {
        $this->applicationId = $applicationId;
    }

    /**
     * Returns the login endpoint for this application
     * @return type
     */
    function getLoginUrl() {
        return 'https://libreid.com/auth/login?ApplicationId=' . $this->applicationId;
    }

    /**
     * Returns the one LibreId token, if exists
     * @return string
     */
    function getOnce() {
        return trim(isset($_REQUEST['once']) ? $_REQUEST['once'] : '');
    }

    /**
     * Returns the user data as array, or the error message as string
     * @return array | string
     */
    function getUserData() {
        $once = $this->getOnce();
      
        if ($once == "") {
            return "Token is missing";
        }

        $url = 'https://libreid.com/api/user-data?once=' . $once;
        $response = json_decode(file_get_contents($url, false, stream_context_create([
            "ssl" => array(
                "verify_peer" => false,
                "verify_peer_name" => false,
            )]
        )));

        if ($response == false) {
            return 'Undefined Response. Please, try again';
        }
        if ($response->status != 'success') {
            return 'Unsuccessful Response. Please, try again';
        }

        $data = $response->data;
        $user = $data->user;
        return $user;
    }

    //========================= START OF METHOD ===========================//
    //  METHOD: is_uuid_valid                                              //
    //=====================================================================//
    private static function isUuidValid($uuid) {
        return preg_match('#^[a-z0-9]{8}(-?)[a-z0-9]{4}(-?)[a-z0-9]{4}(-?)[a-z0-9]{4}(-?)[a-z0-9]{12}$#', $uuid);
    }
    //=====================================================================//
    //  METHOD: is_uuid_valid                                              //
    //========================== END OF METHOD ============================//
}

//===========================================================================//
// CLASS: LibreID                                                            //
//============================== END OF CLASS ===============================//
?>
