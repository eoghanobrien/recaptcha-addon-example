<?php

class Foobar_Feedback_IndexController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
        $this->loadLayout();
        $this->getLayout()->getBlock('foobarFeedbackForm')
            ->setFormAction(Mage::getUrl('*/*/post', array('_secure' => $this->getRequest()->isSecure())));
        $this->renderLayout();
    }
    
    public function postAction()
    {
        if (!$data = $this->getRequest()->getPost()) {
            return $this->_redirectReferer();
        }
        
        $errors = $this->_validateForm($data);
        if (! empty($errors)) {
            Mage::getSingleton('core/session')
                ->setFormData($data)
                ->setFormErrors($errors)
                ->addError(Mage::helper('foobar_feedback')->__('We encountered some errors with the feedback you submitted.'));
            return $this->_redirect('*/');
        }
        
        try {
            //$this->_sendEmail($data);
            Mage::getSingleton('core/session')
                ->setFormData(false)
                ->setFormErrors(false)
                ->addSuccess(Mage::helper('foobar_feedback')->__('Thank you for your feedback.'));
            return $this->_redirect('*/');
        }
        catch (Exception $e) {
            Mage::getSingleton('core/session')
                ->setFormData($data)
                ->setFormErrors(false)
                ->addError(Mage::helper('foobar_feedback')->__('We were unable to send your feedback, please try again later.'));
            return $this->_redirect('*/');
        }
    }
    
    protected function _validateForm($data)
    {
        $errors = array();
        
        if (! Zend_Validate::is($data['name'], 'NotEmpty')) {
            $errors['name'] = Mage::helper('foobar_feedback')->__('This is a required field.');
        }
        
        if (! Zend_Validate::is($data['email'], 'EmailAddress')) {
            $errors['email'] = Mage::helper('foobar_feedback')->__(
                'Please enter a valid email address. For example johndoe@domain.com.'
            );
        }
        
        if (! Zend_Validate::is($data['feedback'], 'NotEmpty')) {
            $errors['feedback'] = Mage::helper('foobar_feedback')->__('This is a required field.');
        }
        
        return $errors;
    }
    
    protected function _sendEmail($data)
    {
        /* @var Mage_Core_Model_Translate $translate */
        $translate = Mage::getSingleton('core/translate');
        $translate->setTranslateInline(false);
        
        $mailTemplate = Mage::getModel('core/email_template');
        /* @var $mailTemplate Mage_Core_Model_Email_Template */
        $mailTemplate->setDesignConfig(array('area' => 'frontend'))
            ->setReplyTo($post['email'])
            ->sendTransactional(
                Mage::getStoreConfig(self::XML_PATH_EMAIL_TEMPLATE),
                Mage::getStoreConfig(self::XML_PATH_EMAIL_SENDER),
                Mage::getStoreConfig(self::XML_PATH_EMAIL_RECIPIENT),
                null,
                array('data' => $data)
            );

        if (!$mailTemplate->getSentSuccess()) {
            throw new Exception('Unable to send the email.');
        }
        
        $translate->setTranslateInline(true);
    }
}
