<?php

namespace app\Exception;

use Max\VarDumper\Dumper;
use Max\VarDumper\DumperHandler;
use support\exception\Handler;
use Throwable;
use Webman\Http\Request;
use Webman\Http\Response;
use Firebase\JWT\SignatureInvalidException;
use Tinywan\Jwt\Exception\JwtTokenException;

class ExceptionHandler extends Handler
{
    use DumperHandler;

    public function render(Request $request, Throwable $exception): Response
    {
        if ($exception instanceof Dumper) {
            return \response(self::convertToHtml($exception));
        }

//        if($exception instanceof JwtTokenException) {
//            return json(['message' => 'Signature verification failed'])->withStatus(401);
//        }

        return parent::render($request, $exception);
    }
}