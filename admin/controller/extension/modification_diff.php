<?php
class ControllerExtensionModificationDiff extends Controller {
    public function index() {
        $this->validate();

        $this->load->language('extension/modification_diff');

        $this->document->setTitle($this->language->get('heading_title'));

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_modifications'),
            'href' => $this->url->link('extension/modification', 'token=' . $this->session->data['token'], true)
        );

        $this->document->addStyle('view/javascript/ace/diff.min.css');
        $this->document->addScript('view/javascript/ace/diff-patch.min.js');

        if (isset($this->request->get['file_patch'])) {
            $file_patch = $this->request->get['file_patch'];

            $patch = DIR_MODIFICATION . $file_patch;
            if (is_file($patch)) {
                $data['file_patch'] = $file_patch;

                ob_start();
                readfile($patch);
                $data['code_cache'] = ob_get_contents();
                ob_end_clean();

                $dir = str_replace('\\', '/', realpath(DIR_APPLICATION . '../')) . '/';
                ob_start();
                readfile($dir . $file_patch);
                $data['code_original'] = ob_get_contents();
                ob_end_clean();

                $data['breadcrumbs'][] = array(
                    'text' => $this->language->get('heading_title'),
                    'href' => $this->url->link('extension/modification_diff', 'token=' . $this->session->data['token'] . '&file_patch=' . $file_patch, true)
                );
            } else {
                $this->response->redirect($this->url->link('extension/modification', 'token=' . $this->session->data['token'], true));
            }
        } else {
            $this->response->redirect($this->url->link('extension/modification', 'token=' . $this->session->data['token'], true));
        }

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_help_diff'] = $this->language->get('text_help_diff');

        $data['button_return'] = $this->language->get('button_return');

        $data['return'] = $this->url->link('extension/modification', 'token=' . $this->session->data['token'], true);

        $data['token'] = $this->session->data['token'];

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('extension/modification_diff.tpl', $data));
    }

    private function validate() {
        if (!$this->user->hasPermission('modify', 'extension/modification_diff')) {
            $this->response->redirect($this->url->link('extension/modification', 'token=' . $this->session->data['token'], true));
        }
    }
}
