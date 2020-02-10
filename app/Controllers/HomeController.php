<?php declare(strict_types = 1);

namespace App\Controllers;

use App\Library\Config;
use App\Library\Email;
use App\Library\ValidateSanitize;
use App\Library\Views;
use App\Models\Interested;
use Laminas\Diactoros\ServerRequest;
use PDO;

class HomeController
{
    private $view;
    private $db;
    
    public function __construct(Views $view, PDO $db)
    {
        $this->view = $view;
        $this->db   = $db;
    }
    
    public function index()
    {
        return $this->view->buildResponse('index', []);
    }
    
    public function interested(ServerRequest $request)
    {
    
        $form = $request->getParsedBody();
        unset($form['__token']); // remove CSRF token or PDO bind fails, too many arguments, Need to do everytime.
        
        // Sanitize and Validate
        // - Sanitize First to Remove "bad" input
        // - Validate Second, if Sanitize empties a field due to
        //   "bad" data that is required then Validate will catch it.
        $validate = new ValidateSanitize();
        $form = $validate->sanitize($form); // only sanitizes strings (other filters available)
    
        $validate->validation_rules(array(
            'FullName'    => 'required|alpha_space|max_len,100|min_len,6',
            'Email'       => 'required|valid_email',
            'Title'       => 'alpha_space|max_len,25',
            'Message'     => 'required'
        ));
        // Add filters for non-strings (integers, float, emails, etc) ALWAYS Trim
        $validate->filter_rules(array(
            'Email'    => 'trim|sanitize_email',
        ));
        $validated = $validate->run($form);
    
        if ($validated === false) {
            $validated['alert'] = 'Sorry, we could not add your contact message.  Please try again.';
            $validated['alert_type'] = 'danger';
            $this->view->flash($validated);
            return $this->view->redirect('/#contact');
        }
        // if validated !== false then validated has the filtered data.
        // use validated instead of form now as you see below.
        
        $interest = new Interested($this->db);
        $found = $interest->findEMail($form['Email']);
        if ($found) {
            $form['alert'] = _('That email address has already been submitted.  Thank You for your submission!');
            $form['alert_type'] = 'warning';
        } else {
            $interested = $interest->add($form);
            if (!$interested) {
                $form['alert'] = _('We encountered an issue adding your form. Please try again.');
                $form['alert_type'] = 'danger';
            } else {
                $form['alert'] = _('Thank you for your interest in Tracksz. We will keep you updated with our progress.');
                $form['alert_type'] = 'success';
                
                $mailer = new Email();
                $message['html'] = $this->view->make('emails/interested');
                $message['plain'] = $this->view->make('emails/plain/interested');
                $mailer->sendEmail($form['Email'], Config::get('company_name'),
                    _('Interested In Tracksz'), $message, ['fullname' => $form['FullName']]);
            }
        }
        
        $this->view->flash($form);
        return $this->view->redirect('/');
    }
}
