<?php
/**
 * Copyright © Alekseon sp. z o.o.
 * http://www.alekseon.com/
 */
namespace Alekseon\CustomFormsBuilder\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Store\Model\ScopeInterface;

/**
 * Class EmailNotification
 * @package Alekseon\CustomFormsBuilder\Model
 */
class EmailNotification
{
    const XML_PATH_NEW_ENTITY_EMAIL_IDENTITY = 'alekseon_custom_forms/new_entity_notification_email/identity';
    const XML_PATH_NEW_ENTITY_EMAIL_TEMPLATE = 'alekseon_custom_forms/new_entity_notification_email/template';
    const XML_PATH_NEW_ENTITY_EMAIL_RECEIVER = 'alekseon_custom_forms/new_entity_notification_email/to';

    /**
     * @var TransportBuilder
     */
    protected $transportBuilder;
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;
    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * EmailNotification constructor.
     * @param TransportBuilder $transportBuilder
     */
    public function __construct(
        TransportBuilder $transportBuilder,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Mail\Template\SenderResolverInterface $senderResolver,
        ScopeConfigInterface $scopeConfig,
        \Psr\Log\LoggerInterface $logger
    )
    {
        $this->transportBuilder = $transportBuilder;
        $this->storeManager = $storeManager;
        $this->senderResolver = $senderResolver;
        $this->scopeConfig = $scopeConfig;
        $this->logger = $logger;
    }

    /**
     * @param array $templateParams
     * @return bool
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\MailException
     */
    public function sendNotificationEmail($templateParams = [])
    {
        $emailsConfig = $this->scopeConfig->getValue(self::XML_PATH_NEW_ENTITY_EMAIL_RECEIVER);
        $emails = explode(',', $emailsConfig);
        return $this->sendEmailTemplate($emails,
            self::XML_PATH_NEW_ENTITY_EMAIL_TEMPLATE,
            self::XML_PATH_NEW_ENTITY_EMAIL_IDENTITY,
            $templateParams
        );
    }

    /**
     * @param $emails
     * @param $templateId
     * @param $sender
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\MailException
     */
    protected function sendEmailTemplate($emails, $templateId, $sender, $templateParams = [])
    {
        $storeId = $this->storeManager->getDefaultStoreView()->getId();
        $templateId = $this->scopeConfig->getValue($templateId);

        if (!is_array($emails)) {
            $emails = [$emails];
        }

        $from = $this->senderResolver->resolve(
            $this->scopeConfig->getValue($sender, ScopeInterface::SCOPE_STORE, $storeId),
            $storeId
        );

        $email = array_pop($emails);

        if (!$email) {
            return false;
        }

        $this->transportBuilder->setTemplateIdentifier($templateId)
            ->setTemplateOptions(
                [
                    'area' => \Magento\Framework\App\Area::AREA_ADMINHTML,
                    'store' => $storeId
                ]
            )
            ->setTemplateVars($templateParams)
            ->setFrom($from)
            ->addTo($email);
        
        foreach ($emails as $email) {
            $this->transportBuilder->addBcc($email);
        }

        $transport = $this->transportBuilder->getTransport();

        try {
            $transport->sendMessage();
            return true;
        } catch ( \Exception $e) {
            $this->logger->critical($e->getMessage());
            return false;
        }
    }
}
