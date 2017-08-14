<?php
/**
 * Created by PhpStorm.
 * User: danixoid
 * Date: 29.06.17
 * Time: 17:52
 */?><!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
Перейдите по <a href="{!! route('exam.show',$exam->id) !!}">ссылке</a> для оценки работника
</body>