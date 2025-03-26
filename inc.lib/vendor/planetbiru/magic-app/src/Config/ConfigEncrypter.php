<?php

namespace MagicApp\Config;

use MagicObject\Exceptions\InvalidParameterException;
use MagicObject\SecretObject;

/**
 * Class ConfigEncrypter
 *
 * This class is responsible for encrypting and decrypting configuration files.
 * It utilizes secret management for various configuration components such as
 * database, mailer, session, and Redis. The class requires a callback function
 * for password handling during the encryption and decryption processes.
 */
class ConfigEncrypter
{
    /**
     * Callback password
     *
     * @var callable
     */
    private $callbaskPassword;
    
    /**
     * Constructor
     *
     * Initializes the ConfigEncrypter with a callback function for password handling.
     *
     * @param callable $callbaskPassword Callback function to retrieve password.
     * @throws InvalidParameterException if the callback is not callable.
     */
    public function __construct($callbaskPassword)
    {
        if (isset($callbaskPassword) && is_callable($callbaskPassword)) {
            $this->callbaskPassword = $callbaskPassword;
        } else {
            throw new InvalidParameterException("Callback function is required");
        }
    }

    /**
     * Encrypt configuration
     *
     * Loads a configuration from a YAML file, encrypts its sensitive components,
     * and writes the encrypted configuration to a specified output path.
     *
     * @param string $inputPath Input configuration path.
     * @param string $outputPath Output configuration path.
     * @return boolean True on success, false on failure.
     */
    public function encryptConfig($inputPath, $outputPath)
    {
        if (file_exists($inputPath)) {
            $config = new SecretObject();
            $config->loadYamlFile($inputPath);
            
            $database = new SecretDatabaseWriter($config->getDatabase(), $this->callbaskPassword);     
            $mailer = new SecretMailerWriter($config->getMailer(), $this->callbaskPassword);
            $session = new SecretSessionWriter($config->getSession(), $this->callbaskPassword);
            $redis = new SecretRedisWriter($config->getRedis(), $this->callbaskPassword);
            
            $config->setDatabase(new SecretObject($database->value()));
            $config->setMailer(new SecretObject($mailer->value()));
            $config->setSession(new SecretObject($session->value()));
            $config->setRedis(new SecretObject($redis->value()));
            file_put_contents($outputPath, $config->dumpYaml());
            return true;
        }
        return false; 
    }
    
    /**
     * Decrypt configuration
     *
     * Loads an encrypted configuration from a YAML file, decrypts its components,
     * and writes the decrypted configuration to a specified output path.
     *
     * @param string $inputPath Input configuration path.
     * @param string $outputPath Output configuration path.
     * @return boolean True on success, false on failure.
     */
    public function decryptConfig($inputPath, $outputPath)
    {
        if (file_exists($inputPath)) {
            $config = new SecretObject();
            $config->loadYamlFile($inputPath);
            
            $database = new SecretDatabaseReader($config->getDatabase(), $this->callbaskPassword);
            $mailer = new SecretMailerReader($config->getMailer(), $this->callbaskPassword);
            $session = new SecretSessionReader($config->getSession(), $this->callbaskPassword);
            $redis = new SecretRedisReader($config->getRedis(), $this->callbaskPassword);
            
            $config->setDatabase(new SecretObject($database->value()));
            $config->setMailer(new SecretObject($mailer->value()));
            $config->setSession(new SecretObject($session->value()));
            $config->setRedis(new SecretObject($redis->value()));
            
            file_put_contents($outputPath, $config->dumpYaml());
            return true;
        }
        return false; 
    }
}
