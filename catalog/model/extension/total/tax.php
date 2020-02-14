<?php
class ModelExtensionTotalTax extends Model {
	public function getTotal($total)
    {
        if (isset($this->session->data['payment_address'])){
            if ($this->session->data['payment_address']["paygroupid"]){
                $groupid = $this->session->data['payment_address']["paygroupid"];
            }
        } else {
            $groupid = $this->customer->getGroupId();
        }

        if ($groupid == 5){
            $this->load->language('extension/total/total');
            $total['totals'][] = array(
                'code' => 'tax',
                'title' => $this->language->get('text_total_vat'),
                'value' => $this->cart->getSubTotal() / 6,
                'sort_order' => 8
            );
        }
    }

}