<?php

class Foobar_Feedback_Model_Observer
{
    /**
     * Add the route for the form to the available selections in configuration.
     *
     * @param Varien_Event_Observer $observer The dispatched observer
     */
    public function addRecaptchaRouteToFoobarFeedbackForm(Varien_Event_Observer $observer)
    {
        $observer->getRoutes()->add(
            'foobarfeedback_index_post',
            Mage::helper('foobar_feedback')->__('Foobar Feedback Form')
        );
    }
    
    /**
     * Run additional logic on a failed recaptcha verification for the foobar feedback form.
     *
     * @param Varien_Event_Observer $observer The dispatched observer
     */
    public function onFailedRecaptchaFoobarFeedbackForm(Varien_Event_Observer $observer)
    {
        $data = $observer->getControllerAction()->getRequest()->getPost();
        Mage::getSingleton('core/session')->setFormData($data);
    }
}
