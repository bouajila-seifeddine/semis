<?php
/**
* NOTICE OF LICENSE
*
*   This file is property of Petr Hucik. You may NOT redistribute the code in any way
*   See license.txt for the complete license agreement
*
* @author    Petr Hucik
* @website   https://www.getdatakick.com
* @copyright Petr Hucik <petr@getdatakick.com>
* @license   see license.txt
* @version   2.1.3
*/
namespace Datakick;
require_once(dirname(__FILE__).'/services/service.php');
require_once(dirname(__FILE__).'/services/configuration.php');
require_once(dirname(__FILE__).'/services/license.php');
require_once(dirname(__FILE__).'/services/get-record.php');
require_once(dirname(__FILE__).'/services/delete-record.php');
require_once(dirname(__FILE__).'/services/get-list.php');
require_once(dirname(__FILE__).'/services/get-xml.php');
require_once(dirname(__FILE__).'/services/save-xml-template.php');
require_once(dirname(__FILE__).'/services/save-endpoint.php');
require_once(dirname(__FILE__).'/services/save-schedule.php');
require_once(dirname(__FILE__).'/services/save-place.php');
require_once(dirname(__FILE__).'/services/save-list.php');
require_once(dirname(__FILE__).'/services/save-mass-update.php');
require_once(dirname(__FILE__).'/services/enable-cron.php');
require_once(dirname(__FILE__).'/services/task-parameters.php');
require_once(dirname(__FILE__).'/services/execute.php');
require_once(dirname(__FILE__).'/services/download.php');
require_once(dirname(__FILE__).'/services/upload-file.php');
require_once(dirname(__FILE__).'/services/save-custom-field.php');
require_once(dirname(__FILE__).'/services/edit-records.php');
require_once(dirname(__FILE__).'/services/set-option.php');
require_once(dirname(__FILE__).'/services/encrypt.php');
require_once(dirname(__FILE__).'/services/change-owner.php');
require_once(dirname(__FILE__).'/services/get-permissions.php');
require_once(dirname(__FILE__).'/services/save-permissions.php');
require_once(dirname(__FILE__).'/services/get-restrictions.php');
require_once(dirname(__FILE__).'/services/save-restrictions.php');
require_once(dirname(__FILE__).'/services/set-configuration.php');
require_once(dirname(__FILE__).'/services/analyze-datasource.php');
require_once(dirname(__FILE__).'/services/import-validate-matching.php');
require_once(dirname(__FILE__).'/services/import-validate-transformation.php');
require_once(dirname(__FILE__).'/services/save-import-definition.php');

class Services {
  private $factory;
  private $services = array();

  public function __construct($factory) {
    $this->factory = $factory;
    $this->register(new ConfigurationService());
    $this->register(new LicenseService());
    $this->register(new GetXmlPreviewService());
    $this->register(new SaveXmlTemplateService());
    $this->register(new SaveListService());
    $this->register(new SaveEndpointService());
    $this->register(new SaveScheduleService());
    $this->register(new GetRecordService());
    $this->register(new DeleteRecordService());
    $this->register(new EditRecordsService());
    $this->register(new GetListService());
    $this->register(new SavePlaceService());
    $this->register(new EnableCronService());
    $this->register(new TaskParametersService());
    $this->register(new ExecuteService());
    $this->register(new DownloadService());
    $this->register(new UploadFileService());
    $this->register(new SaveCustomFieldService());
    $this->register(new SaveMassUpdateService());
    $this->register(new SetOptionService());
    $this->register(new EncryptService());
    $this->register(new ChangeOwnerService());
    $this->register(new GetPermissionsService());
    $this->register(new SavePermissionsService());
    $this->register(new GetRestrictionsService());
    $this->register(new SaveRestrictionsService());
    $this->register(new SetConfigurationService());
    $this->register(new AnalyzeDatasourceService());
    $this->register(new ImportValidateMatchingService());
    $this->register(new ImportValidateTransformationService());
    $this->register(new SaveImportDefinitionService());
  }

  public function register($service) {
    $name = $service->getName();
    $this->services[$name] = $service;
  }

  public function getService($name) {
    if (! isset($this->services[$name])) {
      throw new UserError("Service $name not found");
    }
    return $this->services[$name];
  }

  public function handle($name, $payload) {
    return $this->getService($name)->handle($this->factory, $payload);
  }

  public function payloadType($name) {
    return $this->getService($name)->payloadType();
  }

  public function getServices() {
    return array_keys($this->services);
  }
}
