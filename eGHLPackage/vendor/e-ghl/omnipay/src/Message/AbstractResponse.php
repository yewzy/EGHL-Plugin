<?php

namespace Omnipay\eGHL\Message;

use Symfony\Component\HttpFoundation\Response as HttpResponse;

abstract class AbstractResponse extends \Omnipay\Common\Message\AbstractResponse
{
    public function getRedirectResponse()
    {
        $this->validateRedirect();

        if ('GET' === $this->getRedirectMethod()) {
            return HttpRedirectResponse::create($this->getRedirectUrl());
        }

        $hiddenFields = '';
        foreach ($this->getRedirectData() as $key => $value) {
            $hiddenFields .= sprintf(
                '<input type="hidden" name="%1$s" value="%2$s" />',
                htmlentities($key, ENT_QUOTES, 'UTF-8', false),
                htmlentities($value, ENT_QUOTES, 'UTF-8', false)
            )."\n";
        }

        $output = ' <!DOCTYPE html>
                    <html>
                    <head>
                        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
                        <title>Redirecting...</title>
                    </head>
                    <body onload="document.getElementById(\'eGHL_OmniPay\').submit();">
                        <form id="eGHL_OmniPay" action="%1$s" method="post">
                            <p>Redirecting to payment page...</p>
                            <p>
                                %2$s
                                <input type="submit" value="Continue" />
                            </p>
                        </form>
                    </body>
                    </html>';
        $output = sprintf(
            $output,
            htmlentities($this->getRedirectUrl(), ENT_QUOTES, 'UTF-8', false),
            $hiddenFields
        );

        return HttpResponse::create($output);
    }
}
?>