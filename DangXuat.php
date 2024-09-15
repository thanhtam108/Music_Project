<?php
session_start(); {
    unset($_SESSION['loggedEMP_in']);
    unset($_SESSION['loggedADM_in']);
    unset($_SESSION['loggedCUS_in']);

    header('Refresh: 0.5; URL = index.php');
}
