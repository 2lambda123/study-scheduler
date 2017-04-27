<?php
$mysqli = new mysqli("localhost", "root");

if ($mysqli->connect_errno) {
    printf("Connect failed: %s\n", $mysqli->connect_error);
    exit();
}

if ($mysqli->query('CREATE DATABASE STUDYSCHEDULER')) {
	echo "Database created";
}

if ($mysqli->select_db('STUDYSCHEDULER')) {
	echo "SELECTED DATABASE STUDYSCHEDULER";
}

$sql = "CREATE TABLE USER (" .
	"ID VARCHAR(36) UNIQUE, " .
	"USERNAME VARCHAR(30), " .
	"PASSWORD VARCHAR(36), " .
	"SETTINGS TEXT, " .
	"KTHAUTH TEXT, " .
	"FBAUTH TEXT)";
	
if ($mysqli->query($sql)) {
	echo "TABLE USER Created";
}

/*if ($mysqli->query('CREATE TRIGGER before_create_user BEFORE INSERT ON USER FOR EACH ROW SET new.ID = uuid();')) {
	echo "SUCCESS";
}*/

$sql = "CREATE TABLE DATA (" .
	"ID VARCHAR(36) UNIQUE, " .
	"HABITS TEXT, " .
	"COURSES TEXT, " .
	"ROUTINES TEXT)";
	
if ($mysqli->query($sql)) {
	echo "TABLE DATA Created";
}

$sql = "CREATE TABLE CALENDAR (" .
	"ID VARCHAR(36) UNIQUE, " .
	"STUDY LONGTEXT, " .
	"PERSONAL LONGTEXT, " .
	"HABITS LONGTEXT, " .
	"CURRENT LONGTEXT)";
	
if ($mysqli->query($sql)) {
	echo "TABLE CALENDAR Created";
}

$mysqli->close();
?>