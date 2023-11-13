<?php session_start();
session_unset();
session_destroy();

ECHO "SESSION DESTROYED";