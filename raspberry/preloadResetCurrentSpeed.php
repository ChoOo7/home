#!/usr/bin/env php 
<?php

$optionDir = __DIR__.'/config/';

$bandwidthFilePath = $optionDir.'bandwidthNow';
if(file_exists($bandwidthFilePath))
{
    unlink($bandwidthFilePath);
}