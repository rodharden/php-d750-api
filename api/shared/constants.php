<?php

class Constants
{
    public static $HTTP_MESSAGE_ERROR_NOT_FOUND  = 404;
    public static $HTTP_MESSAGE_ERROR_METHOD_NOT_ALLOWED  = 405;
    public static $HTTP_MESSAGE_ERROR_UNPROCESSABLE_ENTITY = 422; 
    public static $HTTP_MESSAGE_ERROR_INTERNAL_SERVER_ERROR  = 503;
    public static $HTTP_MESSAGE_SUCCESS  = 200;
    

    public static $CRUD_MESSAGE_UPDATE_ERROR = "Unable to update the object";
    public static $CRUD_MESSAGE_UPDATE_SUCCESS = "The object was updated.";
    public static $CRUD_MESSAGE_DELETE_ERROR = "Unable to delete the object";
    public static $CRUD_MESSAGE_DELETE_SUCCESS = "The object was deleted.";

    public static $MESSAGE_USER_REGISTERUSER = "User was successfully registered.";
    public static $MESSAGE_USER_REGISTERUSER_ERROR = "Unable to register the user.";

    public static $FIELD_IDENTITY_MAIN = "id";
    public static $FIELD_IDENTITY_MISSING_ERROR = "id cannot be null";

    public static $DATE_FORMAT_WITH_TIMESTAMP = 'U = Y-m-d H:i:s';
    public static $DATE_FORMAT_DATE_US = 'Y-m-d H:i:s';

}