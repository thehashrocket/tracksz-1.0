<?php declare (strict_types = 1);

namespace App\Controllers\Marketplace;

use App\Library\Config;
use App\Library\Views;
use App\Models\Marketplace\Marketplace;
use App\Library\ValidateSanitize\ValidateSanitize;
use Laminas\Diactoros\ServerRequest;
use Delight\Auth\Auth;
use Delight\Cookie\Session;
use PDO;

class MarketplaceController
{
    private $view;
    private $db;

    public function __construct(Views $view, PDO $db)
    {
        $this->view = $view;
        $this->db = $db;    
    }

    public function view()
    {   
        $result_data = (new Marketplace($this->db))->getActiveUserAll(Session::get('auth_user_id'),[0,1]);
        return $this->view->buildResponse('marketplace/view', ['marketplace' => $result_data]);
    }

    public function add()
    {
        $market_places = Config::get('market_places');
        return $this->view->buildResponse('marketplace/add', ['market_places' => $market_places]);
    }

    public function addSecond(ServerRequest $request)
    { 
        $form = $request->getParsedBody();
        unset($form['__token']); // remove CSRF token or PDO bind fails, too many arguments, Need to do everytime.
     
        if(is_array($form) && $form['MarketName'] == 'Select Marketplace...'){
            $form['MarketName'] = '';
        }

        $validate = new ValidateSanitize();
        $form = $validate->sanitize($form); // only trims & sanitizes strings (other filters available)
      
        $validate->validation_rules(array(
            'MarketName'    => 'required'
        ));

        $validated = $validate->run($form,true);
        // use validated as it is filtered and validated        
        if ($validated === false) {
            $validated['alert'] = 'Sorry, we could not got to next step.  Please try again.';
            $validated['alert_type'] = 'danger';
            $this->view->flash($validated);
            return $this->view->redirect('/marketplace/dashboard');
        }

        $market_price = Config::get('market_price');
        return $this->view->buildResponse('marketplace/add_step_second', ['form' => $form,'market_price' => $market_price]);
    }

    public function addThree(ServerRequest $request)
    {   
        $form = $request->getParsedBody();
        unset($form['__token']); // remove CSRF token or PDO bind fails, too many arguments, Need to do everytime.
     
        $validate = new ValidateSanitize();
        $form = $validate->sanitize($form); // only trims & sanitizes strings (other filters available)
      
        $validate->validation_rules(array(
            'EmailAddress'    => 'required|valid_email',
            'MarketName'    => 'required',
            'SellerID'    => 'required',
            'Password'    => 'required',
            'FtpAddress'    => 'required',
            'FtpId'    => 'required',            
            'FtpPwd'    => 'required',
            'PrependVenue'    => 'required',
            'AppendVenue'    => 'required',
            'IncreaseMinMarket'    => 'required',
            'FileFormat'    => 'required',
            'FtpAppendVenue'    => 'required',
            // 'SuspendExport'    => 'required',
            // 'SendDeletes'    => 'required',
            'MarketAcceptPrice'    => 'required',
            'MarketAcceptPriceVal'    => 'required',
            'MarketAcceptPriceValMulti'    => 'required',
            'MarketSpecificPrice'    => 'required',
            'MarketAcceptPriceVal2'    => 'required',
            'MarketAcceptPriceValMulti2'    => 'required',
        ));

        // Add filters for non-strings (integers, float, emails, etc) ALWAYS Trim
          $validate->filter_rules(array(
            'EmailAddress'    => 'trim|sanitize_email',            
        ));
        
        $validated = $validate->run($form);
        // use validated as it is filtered and validated        
        if ($validated === false) {                     
            $validated['alert'] = 'Sorry, Please fill marketplace data.  Please try again.';
            $validated['alert_type'] = 'danger';
            $this->view->flash($validated);
            $market_price = Config::get('market_price');
            return $this->view->buildResponse('marketplace/add_step_second', ['form' => $form,'market_price' => $market_price]);            
        }
      
        $form_insert_data = array(
            'EmailAddress' => (isset($form['EmailAddress']) && !empty($form['EmailAddress']))?$form['EmailAddress']:null,
            'MarketName' => (isset($form['MarketName']) && !empty($form['MarketName']))?$form['MarketName']:null,
            'SellerID' => (isset($form['SellerID']) && !empty($form['SellerID']))?$form['SellerID']:null,
            'Password' => (isset($form['Password']) && !empty($form['Password']))?$form['Password']:null,
            'FtpAddress' => (isset($form['FtpAddress']) && !empty($form['FtpAddress']))?$form['FtpAddress']:null,
            'FtpUserId' => (isset($form['FtpId']) && !empty($form['FtpId']))?$form['FtpId']:null,
            'FtpPassword' => (isset($form['FtpPwd']) && !empty($form['FtpPwd']))?$form['FtpPwd']:null,
            'PrependVenue' => (isset($form['PrependVenue']) && !empty($form['PrependVenue']))?$form['PrependVenue']:null,
            'AppendVenue' => (isset($form['AppendVenue']) && !empty($form['AppendVenue']))?$form['AppendVenue']:null,
            'IncreaseMinMarket' => (isset($form['IncreaseMinMarket']) && !empty($form['IncreaseMinMarket']))?$form['IncreaseMinMarket']:null,
            'FileFormat' => (isset($form['FileFormat']) && !empty($form['FileFormat']))?$form['FileFormat']:null,
            'FtpAppendVenue' => (isset($form['FtpAppendVenue']) && !empty($form['FtpAppendVenue']))?$form['FtpAppendVenue']:null,
            'SuspendExport' => (isset($form['SuspendExport']) && !empty($form['SuspendExport']))?1:null,
            'SendDeletes' => (isset($form['SendDeletes']) && !empty($form['SendDeletes']))?1:null,
            'MarketAcceptPrice' => (isset($form['MarketAcceptPrice']) && !empty($form['MarketAcceptPrice']))?$form['MarketAcceptPrice']:null,
            'MarketAcceptPriceVal' => (isset($form['MarketAcceptPriceVal']) && !empty($form['MarketAcceptPriceVal']))?$form['MarketAcceptPriceVal']:null,
            'MarketAcceptPriceValMulti' => (isset($form['MarketAcceptPriceValMulti']) && !empty($form['MarketAcceptPriceValMulti']))?$form['MarketAcceptPriceValMulti']:null,
            'MarketSpecificPrice' => (isset($form['MarketSpecificPrice']) && !empty($form['MarketSpecificPrice']))?$form['MarketSpecificPrice']:null,
            'MarketAcceptPriceVal2' => (isset($form['MarketAcceptPriceVal2']) && !empty($form['MarketAcceptPriceVal2']))?$form['MarketAcceptPriceVal2']:null,
            'MarketAcceptPriceValMulti2' => (isset($form['MarketAcceptPriceValMulti2']) && !empty($form['MarketAcceptPriceValMulti2']))?$form['MarketAcceptPriceValMulti2']:null,
            'Status' => (isset($form['MarketStatus']) && $form['MarketStatus'] == 1)?$form['MarketStatus']:0,
            'UserId' => Session::get('auth_user_id'),
        );

        $inserted_result = (new Marketplace($this->db))->addMarketplace($form_insert_data);

        if(isset($inserted_result) && $inserted_result){
            return $this->view->buildResponse('marketplace/add_step_three');
        }else{
            $validated['alert'] = 'Sorry, we could not add marketplace.  Please try again.';
            $validated['alert_type'] = 'danger';
            $this->view->flash($validated);
            return $this->view->redirect('/marketplace/dashboard/step2');
        }
        
        $this->view->flash($validated);
        return $this->view->redirect('/marketplace/dashboard/step2');
    }

    public function editMarketplace(ServerRequest $request, $Id = [])
    {          
        $form = (new Marketplace($this->db))->findById($Id['Id']);
        if(is_array($form) && !empty($form)){
            $market_price = Config::get('market_price');            
            return $this->view->buildResponse('/marketplace/edit', [
                'form' => $form,'market_price' => $market_price
            ]);
        }else{
            $this->view->flash([
                        'alert' => 'Failed to fetch markerplace details. Please try again.',
                        'alert_type' => 'danger'
                    ]);
                    return $this->view->buildResponse('/marketplace/list');
        }
    }


    public function updateMarketplace(ServerRequest $request, $Id = [])
    {      
        $methodData = $request->getParsedBody();
        unset($methodData['__token']); // remove CSRF token or PDO bind fails, too many arguments, Need to do everytime.

        $form_udpate_data = array(
            'Id' => (isset($methodData['Id']) && !empty($methodData['Id']))?$methodData['Id']:null,
            'EmailAddress' => (isset($methodData['EmailAddress']) && !empty($methodData['EmailAddress']))?$methodData['EmailAddress']:null,
           'MarketName' => (isset($methodData['MarketName']) && !empty($methodData['MarketName']))?$methodData['MarketName']:null,           
            'SellerID' => (isset($methodData['SellerID']) && !empty($methodData['SellerID']))?$methodData['SellerID']:null,
            'Password' => (isset($methodData['Password']) && !empty($methodData['Password']))?$methodData['Password']:null,
            'FtpAddress' => (isset($methodData['FtpAddress']) && !empty($methodData['FtpAddress']))?$methodData['FtpAddress']:null,
            'FtpUserId' => (isset($methodData['FtpId']) && !empty($methodData['FtpId']))?$methodData['FtpId']:null,
            'FtpPassword' => (isset($methodData['FtpPwd']) && !empty($methodData['FtpPwd']))?$methodData['FtpPwd']:null,
            'PrependVenue' => (isset($methodData['PrependVenue']) && !empty($methodData['PrependVenue']))?$methodData['PrependVenue']:null,
            'AppendVenue' => (isset($methodData['AppendVenue']) && !empty($methodData['AppendVenue']))?$methodData['AppendVenue']:null,
            'IncreaseMinMarket' => (isset($methodData['IncreaseMinMarket']) && !empty($methodData['IncreaseMinMarket']))?$methodData['IncreaseMinMarket']:null,
            'FileFormat' => (isset($methodData['FileFormat']) && !empty($methodData['FileFormat']))?$methodData['FileFormat']:null,
            'FtpAppendVenue' => (isset($methodData['FtpAppendVenue']) && !empty($methodData['FtpAppendVenue']))?$methodData['FtpAppendVenue']:null,
            'SuspendExport' => (isset($methodData['SuspendExport']) && !empty($methodData['SuspendExport']))?1:null,
            'SendDeletes' => (isset($methodData['SendDeletes']) && !empty($methodData['SendDeletes']))?1:null,
            'MarketAcceptPrice' => (isset($methodData['MarketAcceptPrice']) && !empty($methodData['MarketAcceptPrice']))?$methodData['MarketAcceptPrice']:null,
            'MarketAcceptPriceVal' => (isset($methodData['MarketAcceptPriceVal']) && !empty($methodData['MarketAcceptPriceVal']))?$methodData['MarketAcceptPriceVal']:null,
            'MarketAcceptPriceValMulti' => (isset($methodData['MarketAcceptPriceValMulti']) && !empty($methodData['MarketAcceptPriceValMulti']))?$methodData['MarketAcceptPriceValMulti']:null,
            'MarketSpecificPrice' => (isset($methodData['MarketSpecificPrice']) && !empty($methodData['MarketSpecificPrice']))?$methodData['MarketSpecificPrice']:null,
            'MarketAcceptPriceVal2' => (isset($methodData['MarketAcceptPriceVal2']) && !empty($methodData['MarketAcceptPriceVal2']))?$methodData['MarketAcceptPriceVal2']:null,
            'MarketAcceptPriceValMulti2' => (isset($methodData['MarketAcceptPriceValMulti2']) && !empty($methodData['MarketAcceptPriceValMulti2']))?$methodData['MarketAcceptPriceValMulti2']:null,
            'Status' => (isset($methodData['MarketStatus']) && $methodData['MarketStatus'] == 1)?$methodData['MarketStatus']:0,
            'Updated' => date('Y-m-d H:i:s')
        );  

        $is_updated = (new Marketplace($this->db))->editMarket($form_udpate_data);
        if(isset($is_updated) && !empty($is_updated)){
            $this->view->flash([
                'alert' => 'Marketplace record updated successfully..!',
                'alert_type' => 'success'
            ]);
            $result_data = (new Marketplace($this->db))->getAll();
            return $this->view->buildResponse('marketplace/view', ['marketplace' => $result_data]);
        }else{
            $this->view->flash([
                        'alert' => 'Failed to update marketplace. Please ensure all input is filled out correctly.',
                        'alert_type' => 'danger'
                    ]);
                    return $this->view->buildResponse('/marketplace/edit', [
                        'update_id' => $methodData['update_id'],
                        'update_name' => $methodData['Name'],
                        'update_delivery' => $methodData['DeliveryTime'],
                        'update_fee' => $methodData['InitialFee'],
                        'update_discount_fee' => $methodData['DiscountFee'],
                        'update_minimum' => $methodData['Minimum']
                    ]);

        }
    }


    public function deleteMarketData(ServerRequest $request){
        $form = $request->getParsedBody();
        $result_data = (new Marketplace($this->db))->delete($form['Id']);        
        if(isset($result_data) && !empty($result_data)){

            $validated['alert'] = 'Marketplace record deleted successfully..!';
            $validated['alert_type'] = 'success';
            $this->view->flash($validated);

            $res['status'] = true;
            $res['data'] = array();
            $res['message'] = 'Records deleted successfully..!';                
            echo json_encode($res);
            exit;
        }else{
            $validated['alert'] = 'Sorry, Marketplace records not deleted..! Please try again.';
            $validated['alert_type'] = 'danger';
            $this->view->flash($validated);

            $res['status'] = false;
            $res['data'] = array();
            $res['message'] = 'Records not Deleted..!';                
            echo json_encode($res);
            exit;

        }
    }

}
