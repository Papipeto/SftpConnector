<?php
namespace SftpConnector;

use ScriptFUSION\Porter\Connector\Connector;
use ScriptFUSION\Porter\Options\EncapsulatedOptions;
use SftpConnector\Ssh2\Ssh2Adapter;

/**
 * Fetches data form an SFTP server via libssh2 library.
 *
 * @link https://github.com/phpseclib/phpseclib
 */
class SftpConnector implements Connector
{
    /**
     * {@inheritdoc}
     *
     * @param string $source Path to the file.
     * @param SftpOptions $options Mandatory options to connect to the FTP.
     *
     * @return resource Response.
     *
     * @throws \InvalidArgumentException Options is not an instance of SftpOptions.
     * @throws Ssh2ConnectionException Couldn't connect to the server.
     */
    public function fetch($source, EncapsulatedOptions $options = null)
    {
        if (!$options || !$options instanceof SftpOptions) {
            throw new \InvalidArgumentException('Options must be an instance of SftpOptions.');
        }

        $ssh2Adapter = new Ssh2Adapter;
        $ssh2Adapter->connect($options->getHost(), $options->getPort());

        $resource = ssh2_sftp($ssh2Adapter->authenticate($options)->getSession());

        return fopen("ssh2.sftp://$resource/$source", 'r');
    }
}
