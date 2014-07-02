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
        $this->messagePlain = chunk_split(strip_tags($messagePlain));

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
        $this->messageHtml = chunk_split($messageHtml);

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
        $boundary = $this->getBoundary();

        // $message = array(
        //     'plain' => $this->getMessagePlain(),
        //     'html'  => $this->getMessageHtml(),
        // );

        // $this->addHeader(sprintf(
        //     'Content-Type: multipart/alternative; boundary=%s',
        //     $boundary
        // ));

        $html = sprintf(
            '%s %s %s %s %s',
            'My message <b>html</b> this.. ',
            'My message <b>html</b> this.. ',
            'My message <b>html</b> this.. ',
            'My message <b>html</b> this.. ',
            'My message <b>html</b> this..'
        );

        $this
            ->addHeader('MIME-Version: 1.0')
            ->addHeader(sprintf(
                'Content-Type: multipart/alternative; boundary=%s',
                $boundary
            ) . self::CLRF)


            ->addHeader(sprintf(
                '%s',
                'This is a MIME encoded message.'
            ) . self::CLRF)


            ->addHeader(sprintf(
                '--%s',
                $boundary
            ))
            ->addHeader(sprintf(
                '%s',
                'Content-Type: text/plain; charset=utf-8'
            ))
            ->addHeader(sprintf(
                '%s',
                'Content-Transfer-Encoding: base64'
            ) . self::CLRF)
            ->addHeader(chunk_split(base64_encode(strip_tags($html))))






            ->addHeader(sprintf(
                '--%s',
                $boundary
            ))
            ->addHeader(sprintf(
                '%s',
                'Content-Type: text/html; charset=utf-8'
            ))
            ->addHeader(sprintf(
                '%s',
                'Content-Transfer-Encoding: base64'
            ) . self::CLRF)
            ->addHeader(chunk_split(base64_encode($html)))
        ;


        // ->addHeader('Content-Type: text/html; charset=utf-8')
        // ->addHeader('Content-Transfer-Encoding: quoted-printable')

        $headerStr = ''
            . $this->getHeaderAsString()
            // . self::CLRF
            // . $message['plain']
            // . self::CLRF
            // . $message['html']
        ;

        return base64_encode(trim($headerStr));
    }
}
