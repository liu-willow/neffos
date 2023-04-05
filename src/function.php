<?php

function msgId() :string {
    return implode('', ['$', md5(microtime(true))]);
}
