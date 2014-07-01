<?php

namespace Cekurte\ZCPEBundle\Mailer;

/**
 * Mailer Message RFC 822
 *
 * @author João Paulo Cercal <sistemas@cekurte>
 * @version 1.0
 *
 * @see http://www.ietf.org/rfc/rfc0822.txt
 */
class MessageRFC822
{
    /**
     * @see http://www.ietf.org/rfc/rfc0822.txt
     */
    const CLRF = "\r\n";

    /**
     * @see http://www.ietf.org/rfc/rfc0822.txt
     */
    const MAX_LINE_LENGTH = 65;

    /**
     * @var array
     */
    protected $header;

    /**
     * @var string
     */
    protected $messagePlain;

    /**
     * @var string
     */
    protected $messageHtml;

    /**
     * Constructor
     *
     * @param ContainerInterface $container
     */
    public function __construct()
    {
        $this->header        = array();
        $this->messagePlain  = '';
        $this->messageHtml   = '';
    }

    /**
     * Gets the formatted message.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getRawData();
    }

    /**
     * Gets the value of header.
     *
     * @return array
     */
    public function getHeader()
    {
        return $this->header;
    }

    /**
     * Gets the value of header.
     *
     * @return string
     */
    public function getHeaderAsString()
    {
        $result     = '';

        $headers    = $this->getHeader();

        foreach ($headers as $header) {
            $result .= $header . self::CLRF;
        }

        return $result;
    }

    /**
     * Add the value in the header.
     *
     * @param string $header the header
     *
     * @return MessageRFC822
     */
    public function addHeader($header)
    {
        $this->header[] = $header;

        return $this;
    }

    /**
     * Gets the value of messagePlain.
     *
     * @return string
     */
    public function getMessagePlain()
    {
        return $this->messagePlain;
    }

    /**
     * Sets the value of messagePlain.
     *
     * @param string $messagePlain the message plain
     *
     * @return MessageRFC822
     */
    public function setMessagePlain($messagePlain)
    {
        $this->messagePlain = $messagePlain;

        return $this;
    }

    /**
     * Gets the value of messageHtml.
     *
     * @return string
     */
    public function getMessageHtml()
    {
        return $this->messageHtml;
    }

    /**
     * Sets the value of messageHtml.
     *
     * @param string $messageHtml the message html
     *
     * @return MessageRFC822
     */
    public function setMessageHtml($messageHtml)
    {
        $this->messageHtml = $messageHtml;

        return $this;
    }

    public function getBoundary()
    {
        return uniqid('CEKURTE_ZCPE');
    }

    /**
     * Gets the formatted message, this code was adapted of PHPMailer library, where the method is: SMTP::data($msg_data).
     *
     * @return string
     *
     * @see https://github.com/PHPMailer/PHPMailer/blob/master/class.smtp.php
     */
    public function getRawData()
    {
        // $boundary = $this->getBoundary();

        // $message = array(
        //     'plain' => $this->getMessagePlain(),
        //     'html'  => $this->getMessageHtml(),
        // );

        // $this->addHeader(sprintf(
        //     'Content-Type: multipart/alternative; boundary=%s',
        //     $boundary
        // ));



        // ->addHeader('Content-Type: text/html; charset=utf-8')
        // ->addHeader('Content-Transfer-Encoding: quoted-printable')

        $headerStr = ''
            . $this->getHeaderAsString()
            // . self::CLRF
            // . $message['plain']
            // . self::CLRF
            // . $message['html']
        ;

        // Normalize line breaks before exploding
        $lines = explode("\n", str_replace(array(self::CLRF, "\r"), "\n", $headerStr));

        /* To distinguish between a complete RFC822 message and a plain message body, we check if the first field
         * of the first line (':' separated) does not contain a space then it _should_ be a header and we will
         * process all lines before a blank line as headers.
         */

        $field = substr($lines[0], 0, strpos($lines[0], ':'));
        $in_headers = false;
        if (!empty($field) && strpos($field, ' ') === false) {
            $in_headers = true;
        }

        $lineResult = '';

        foreach ($lines as $line) {
            $lines_out = array();
            if ($in_headers and $line == '') {
                $in_headers = false;
            }
            // ok we need to break this line up into several smaller lines
            //This is a small micro-optimisation: isset($str[$len]) is equivalent to (strlen($str) > $len)
            while (isset($line[self::MAX_LINE_LENGTH])) {
                //Working backwards, try to find a space within the last self::MAX_LINE_LENGTH chars of the line to break on
                //so as to avoid breaking in the middle of a word
                $pos = strrpos(substr($line, 0, self::MAX_LINE_LENGTH), ' ');
                if (!$pos) { //Deliberately matches both false and 0
                    //No nice break found, add a hard break
                    $pos = self::MAX_LINE_LENGTH - 1;
                    $lines_out[] = substr($line, 0, $pos);
                    $line = substr($line, $pos);
                } else {
                    //Break at the found point
                    $lines_out[] = substr($line, 0, $pos);
                    //Move along by the amount we dealt with
                    $line = substr($line, $pos + 1);
                }
                /* If processing headers add a LWSP-char to the front of new line
                 * RFC822 section 3.1.1
                 */
                if ($in_headers) {
                    $line = "\t" . $line;
                }
            }
            $lines_out[] = $line;

            // Send the lines to the server
            foreach ($lines_out as $line_out) {
                //RFC2821 section 4.5.2
                if (!empty($line_out) and $line_out[0] == '.') {
                    $line_out = '.' . $line_out;
                }

                $lineResult .= $line_out . self::CLRF;
            }
        }

        return $lineResult;
    }
}
