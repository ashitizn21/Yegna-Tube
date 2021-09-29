<?php

class Account
{
    private $fn, $ln, $us, $em, $em2, $pw, $pw2;

    public function __construct($fn, $ln, $us, $em, $em2, $pw, $pw2)
    {
        $this->fn = $fn;
        $this->ln = $ln;
        $this->us = $us;
        $this->em = $em;
        $this->em2 = $em2;
        $this->pw = $pw;
        $this->pw2 = $pw2;
    }

    
}

?>