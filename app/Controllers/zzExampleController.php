<?php declare(strict_types = 1);

namespace App\Controllers;

// Add only the Classes you require in this current class
use App\Library\Config;
use App\Library\ValidateSanitize\ValidateSanitize;
use App\Library\Views;
use App\Models\zzExampleModel;
use Laminas\Diactoros\ServerRequest;
use PDO;

class zzExampleController
{
    private $view;
    private $db;
    
    public function __construct(Views $view, PDO $db)
    {
        $this->view = $view;
        $this->db   = $db;
    }
    
    /**
     * Find - Display the find a name form
     *
     * @return view views/default/zzFind
     */
    public function zzFind()
    {
        return $this->view->buildResponse('zzFind', []);
    }
    
    /**
     * zzPage - Finds example data, displays a view, uses pagination
     *
     * @param ServerRequest $request - sent by default. Not used in this function
     * @param array $arguments - Contains pg = current page, per = rows per page for pagination
     * @return view views/default/zzPage, array of names
     */
    public function zzPage(ServerRequest $request, $arguments=[])
    {
        // When $arguments are in the path example: /example/page/argument1/argument2
        // In most cases, you do not need to $request->getParsedBody();
        // $form = $request->getParsedBody();
        // unset($form['__token']); // remove CSRF token, PDO bind fails, Need to do every time.
        
        // Sanitize and Validate
        // - Sanitize First to Remove "bad" input
        // - Validate Second, if Sanitize empties a field due to
        //   "bad" data that is required then Validate will catch it.
        $validate = new ValidateSanitize();
        $form = $validate->sanitize($arguments); // only trims & sanitizes strings (other filters available)
    
        // if either is empty, change both to defaults.
        if (!isset($arguments['pg']) || !isset($arguments['per'])) {
            $arguments['pg'] = 1;
            $arguments['per'] = Config::get('page_rows');
        }
        $validate->validation_rules(array(
            'pg'    => 'required',
            'per'   => 'required',
        ));
        // Add filters for non-strings (integers, float, emails, etc)
        $validate->filter_rules(array(
            'pg'    => 'sanitize_numbers',
            'per'   => 'sanitize_numbers',
        ));
        $validated = $validate->run($arguments);
        // use validated for rest of function as it is filtered and validated
        
        $zpage = new zzExampleModel($this->db);
        $zpages = $zpage->all($validated['pg'], $validated['per']); // use $validated now
        // could use one line
        // $zpages = (new zzExampleModel($this->db))->all($validated['pg'], $validated['per']);
        
        return $this->view->buildResponse('/zzExample', ['zPage' => $zpages]);
    }
    
    
    /**
     * findExample
     *
     * @param ServerRequest $request - contains an array of form fields submitted
     * @return view views/default/zzExample, array of names found
     */
    public function findExample(ServerRequest $request)
    {
        $form = $request->getParsedBody();
        unset($form['__token']); // remove CSRF token or PDO bind fails, Need to do every time.

        // Sanitize and Validate
        // - Sanitize First to Remove "bad" input
        // - Validate Second, if Sanitize empties a field due to
        //   "bad" data that is required then Validate will catch it.
        $validate = new ValidateSanitize();
        $form = $validate->sanitize($form); // only trims & sanitizes strings (other filters available)
        
        $validate->validation_rules(array(
            'ExampleName'   => 'required|max_len,255',
        ));
        // Add filters for non-strings (integers, float, emails, etc)
        // No need to filter as only one string in form
        //$validate->filter_rules(array(
        //));
        $validated = $validate->run($form);
        // use validated for rest of function as it is filtered and validated
        if ($validated === false) {
            $validated['alert'] = 'Please try again';
            $validated['alert_type'] = 'danger';
            $this->view->flash($validated); // flash validated
            return $this->view->redirect('/example/find');
        }
        
        $zpages = (new zzExampleModel($this->db))->find($validated['ExampleName']); // use $validated
        if (empty($zpages)) {
            $validated['alert'] = 'Sorry, we could not find that name.  Please try again.';
            $validated['alert_type'] = 'danger';
            $this->view->flash($validated);
            return $this->view->redirect('/example/find');
        }
        
        return $this->view->buildResponse('/zzExample', ['zPage' => $zpages]);
    }
}
