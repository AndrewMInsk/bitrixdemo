<?php
use Bitrix\Main\Loader;
use Bitrix\Iblock\SectionTable;

define('ASSETS_DIR', '/local/templates/dist');
define('PHONE_GENERAL_TEXT', '8-495-939-20-15');
define('PHONE_GENERAL_HREF', '+74959392015');
define('PHONE_RESIDENT_TEXT', '8-915-339-15-13');
define('PHONE_RESIDENT_HREF', '+79153391513');
define('EMAIL', 'info@sticmsu.ru');
define('EMAIL_HREF', 'mailto:info@sticmsu.ru');
define('ADDRESS', '119607, г. Москва, вн. тер. г. муниципальный округ Раменки, б-р Раменский, д. 1, стр. 1.');
define('TELEGRAM_LINK', 'https://t.me/sticmsu');
define('VK_LINK', 'https://vk.com/sticmsu');
define('CAPTCHA_PRIVATE_KEY', '6LdcBywsAAAAAOnkUb0Hiw6aHgR3H6U2lgCpw_RA');
define('CAPTCHA_SITE_VERIFY_URL', 'https://www.google.com/recaptcha/api/siteverify');
AddEventHandler("main", "OnBeforeEventSend", "MyOnBeforeEventSendHandler");

function MyOnBeforeEventSendHandler(&$arFields, &$arTemplate)
{
    // Записываем данные в системный журнал
    CEventLog::Add(array(
        "SEVERITY" => "INFO",
        "AUDIT_TYPE_ID" => "MAIL_MONITOR",
        "MODULE_ID" => "main",
        "ITEM_ID" => $arTemplate["EVENT_NAME"],
        "DESCRIPTION" => "Отправка письма. Поля: " . print_r($arFields, true) . " Шаблон: " . print_r($arTemplate, true),
    ));
}
function checkCaptcha($token) {
	$url = CAPTCHA_SITE_VERIFY_URL;

	// The data to be sent
	$data = [
		'secret' => CAPTCHA_PRIVATE_KEY,
		'response' => $token
	];

	// URL-encode the data
	$post_data = http_build_query($data);

	// Create stream context options
	$options = [
		'http' => [
			'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
			'method'  => 'POST',
			'content' => $post_data,
		],
	];

	// Create the context
	$context  = stream_context_create($options);

	// Perform the POST request and get the response
	$response = file_get_contents($url, false, $context);

	// Check if the request was successful
	if ($response === false) {
		return false;
	} else {
		return json_decode($response, true);
	}
}

function getResident()
{
    if (!CModule::IncludeModule("iblock")) {
        return array('success' => false, "msg" => "Модуль iblock не подключен");
    }
    $iblockId = 4;
    $arSelect = array("ID", "NAME", "PREVIEW_TEXT", "PROPERTY_IMAGE", "PROPERTY_WEBSITE","PROPERTY_CLUSTER");
    $arFilter = array("IBLOCK_ID" => $iblockId, "ACTIVE" => "Y");
    if (isset($_GET['id']) && is_numeric($_GET['id'])) {
        $arFilter["ID"] = intval($_GET['id']);
    }
    else {
        $result = array('success' => false,"msg" => "Элемент не найден");
        return $result;
    }
    $rsElements = CIBlockElement::GetList(array(), $arFilter, false, array("nTopCount" => 1), $arSelect);
    if ($element = $rsElements->GetNextElement()) {
        $arFields = $element->GetFields();


        $imageFile = CFile::GetFileArray($arFields["PROPERTY_IMAGE_VALUE"]);
        if (!$imageFile) {
            $imageFile = [];
            $imageFile['SRC'] = ASSETS_DIR . '/assets/images/image-73.jpg';
        }
        $result = array(
            "name" => $arFields["NAME"],
            "description" => $arFields["PREVIEW_TEXT"],
            "image" => $imageFile ? $imageFile["SRC"] : "",
            "url" => $arFields["PROPERTY_WEBSITE_VALUE"]
        );
    } else {
        $result = array('success' => false, "msg" => "Элемент не найден");
    }
    return $result;
}


function becomeResident(){
    try {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return ['success' => false, 'msg' => 'Метод не поддерживается'];
        }

        if (!CModule::IncludeModule("form")) {
            return ['success' => false, 'msg' => 'Модуль form не подключен'];
        }

        if (!CModule::IncludeModule("iblock")) {
            return ['success' => false, 'msg' => 'Модуль iblock не подключен'];
        }

		$ret = captchaValidation();

		if (is_array($ret)){
			return $ret;
		}


        $arFields = array(
            "form_text_11" => $_POST['full_name'] ?? '',
            "form_text_20" => $_POST['business_area'] ?? '',
            "form_text_21" => $_POST['cluster'],
            "form_text_22" => $_POST['last_name'] ?? '',
            "form_text_23" => $_POST['first_name'] ?? '',
            "form_text_24" => $_POST['middle_name'] ?? '',
            "form_email_25" => $_POST['email'] ?? '',
            "form_text_26" => $_POST['phone'] ?? '',
            "form_textarea_27" => $_POST['project_description'] ?? ''
        );

        $RESULT_ID = CFormResult::Add(3, $arFields);

        if ($RESULT_ID) {
            CFormResult::Mail($RESULT_ID);
            return ['success' => true, 'msg' => $RESULT_ID];
        } else {
			global $strError;
            return ['success' => false, 'msg' => 'Ошибка при сохранении результата формы: '.$strError];
        }
    }
    catch (Exception $e) {
        return ['success' => false, 'msg' => $e->getMessage()];
    }

}
function getResidentReports($residentId){
    try {
        global $USER;
        if (!$USER->IsAuthorized()) {
            return ['success' => false, 'msg' => 'Пользователь не авторизован'];
        }

        if (!isManager()) {
            return ['success' => false, 'msg' => 'Доступ запрещен'];
        }

        if (!CModule::IncludeModule("form")) {
            return ['success' => false, 'msg' => 'Модуль form не подключен'];
        }

        if (!CModule::IncludeModule("iblock")) {
            return ['success' => false, 'msg' => 'Модуль iblock не подключен'];
        }

        $residentId = @intval($residentId);
        if ($residentId != -1 && !$residentId) {
            return ['success' => false, 'msg' => 'ID резидента не указан'];
        }

        $WEB_FORM_ID = 6;

        $arFilter = $residentId == -1 ? array() : array("USER_ID" => $residentId);
        $arFieldFilter = array();

        $rsResults = CFormResult::GetList($WEB_FORM_ID, "s_timestamp", "desc", $arFilter, $arFieldFilter, "Y");

        // Получаем companyName из элемента резидента (только если не -1)
        $companyName = '';
        if ($residentId != -1) {
            $userFields = CUser::GetByID($residentId)->Fetch();
            if ($userFields && $userFields['UF_RESIDENT_TO_ELEMENT']) {
                $element = CIBlockElement::GetByID($userFields['UF_RESIDENT_TO_ELEMENT'])->GetNext();
                $companyName = $element['NAME'] ?? '';
            }
        }

        $reports = array();
        while ($arResult = $rsResults->Fetch()) {
            $resultId = $arResult['ID'];

            $arAnswer = CFormResult::GetDataByID($resultId, array(), $arResultData, $arAnswer2);

            // Извлекаем данные из формы
            $reportData = [
                'id' => (int)$resultId,
                'createdAt' => $arResult['DATE_CREATE'] ? (new DateTime($arResult['DATE_CREATE']))->format('Y-m-d\TH:i:s') : '',
                'reportYear' => $arAnswer2['GOD']['56']['USER_TEXT'] ?? '',
                'metrics' => [
                    'revenue' => [
                        'plan' => $arAnswer2['VYRUCHKA_PLAN']['58']['USER_TEXT'] ?? '',
                        'fact' => $arAnswer2['VYRUCHKA_FAKT']['57']['USER_TEXT'] ?? '',
                    ],
                    'invest_infra' => [
                        'plan' => $arAnswer2['INVESTICII_PLAN']['59']['USER_TEXT'] ?? '',
                        'fact' => $arAnswer2['INVESTICII_FAKT']['60']['USER_TEXT'] ?? '',
                    ],
                    'invest_rnd' => [
                        'plan' => $arAnswer2['INVESTICII_NIOKR_PLAN']['62']['USER_TEXT'] ?? '',
                        'fact' => $arAnswer2['INVESTICII_NIOKR_FAKT']['61']['USER_TEXT'] ?? '',
                    ],
                    'employees' => [
                        'plan' => $arAnswer2['CHISLENNOST_PLAN']['63']['USER_TEXT'] ?? '',
                        'fact' => $arAnswer2['CHISLENNOST_FAKT']['64']['USER_TEXT'] ?? '',
                    ],
                    'export_share' => [
                        'plan' => $arAnswer2['EXPORT_PLAN']['66']['USER_TEXT'] ?? '',
                        'fact' => $arAnswer2['EXPORT_FAKT']['65']['USER_TEXT'] ?? '',
                    ],
                ],
                'revenue_dev' => $arAnswer2['VYRUCHKA_DEV']['77']['USER_TEXT'] ?? '',
                'invest_infra_dev' => $arAnswer2['INVESTICII_DEV']['76']['USER_TEXT'] ?? '',
                'invest_rnd_dev' => $arAnswer2['INVESTICII_NIOKR_DEV']['75']['USER_TEXT'] ?? '',
                'employees_dev' => $arAnswer2['CHISLENNOST_DEV']['74']['USER_TEXT'] ?? '',
                'export_share_dev' => $arAnswer2['EXPORT_DEV']['73']['USER_TEXT'] ?? '',
                'comment' => $arAnswer2['COMMENT']['67']['USER_TEXT'] ?? '',
                'projectNames' => $arAnswer2['PROJECT']['68']['USER_TEXT'] ?? '',
                'projectReadiness' => $arAnswer2['STADIA']['69']['USER_TEXT'] ?? '',
                'rids' => $arAnswer2['PERECHEN']['70']['USER_TEXT'] ?? '',
                'commercialization' => $arAnswer2['NTD']['71']['USER_TEXT'] ?? '',
                'stateSupport' => $arAnswer2['PODDERJKA']['72']['USER_TEXT'] ?? '',
            ];

            // Обработка вложений
            $attachments = [];
            $fileFieldIds = [85, 86, 87, 88, 89, 90, 91, 92, 93, 94, 95, 96, 97, 103, 104];
            foreach ($fileFieldIds as $fieldId) {
                if (isset($arAnswer2['FILE_IDS'][$fieldId]['USER_FILE_ID']) && $arAnswer2['FILE_IDS'][$fieldId]['USER_FILE_ID']) {
                    $file = CFile::GetFileArray($arAnswer2['FILE_IDS'][$fieldId]['USER_FILE_ID']);
                    if ($file) {
                        $attachments[] = [
                            'id' => $arAnswer2['FILE_IDS'][$fieldId]['USER_FILE_ID'],
                            'name' => $file['ORIGINAL_NAME'] ?: $file['FILE_NAME'],
                            'url' => $file['SRC'],
                            'size' => round($file['FILE_SIZE'] / 1048576, 2) . ' MB'
                        ];
                    }
                }
            }
            $reportData['attachments'] = $attachments;
            $reportResidentId = $arResult['USER_ID'];
            $reportCompanyName = '';
            $userFields = CUser::GetByID($reportResidentId)->Fetch();
            if ($userFields && $userFields['UF_RESIDENT_TO_ELEMENT']) {
                $element = CIBlockElement::GetByID($userFields['UF_RESIDENT_TO_ELEMENT'])->GetNext();
                $reportCompanyName = $element['NAME'] ?? '';
            }
            $reportData['residentId'] = $reportResidentId;
            $reportData['residentName'] = $reportCompanyName;

            $reports[] = $reportData;
        }

            return ['success' => true, 'data' => ['reports' => $reports]];

    }
    catch (Exception $e) {
        return ['success' => false, 'msg' => $e->getMessage()];
    }
}
function reportFormList(){
    try {
        global $USER;
        if (!$USER->IsAuthorized()) {
            return ['success' => false, 'msg' => 'Пользователь не авторизован'];
        }

        if (!CModule::IncludeModule("form")) {
            return ['success' => false, 'msg' => 'Модуль form не подключен'];
        }

        $userId = $USER->GetID();
        $WEB_FORM_ID = 6;

        $arFilter = array();
        $arFieldFilter = array("form_text_84" => $userId);

        $rsResults = CFormResult::GetList($WEB_FORM_ID, "s_timestamp", "desc", $arFilter, $arFieldFilter, "Y");

        $results = array();
        while ($arResult = $rsResults->Fetch()) {
            $resultId = $arResult['ID'];

            $arAnswer = CFormResult::GetDataByID($resultId, array(), $arResultData, $arAnswer2);

            $year = $arAnswer2['GOD']['56']['USER_TEXT'] ?? '';
            $userIdFromForm = $arAnswer2['SENDER_ID']['84']['USER_TEXT'] ?? '';
            $createdAt = $arResult['DATE_CREATE'];

            $date = new DateTime($createdAt);
            $createdAtIso = $date->format('Y-m-d\TH:i:s');

            $results[] = array(
                'id' => (int)$resultId,
                'createdAt' => $createdAtIso,
                'year' => (int)$year,
            );
        }

        return $results;
    }
    catch (Exception $e) {
        return ['success' => false, 'msg' => $e->getMessage()];
    }
}
function reservationForm(){
    try {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return ['success' => false, 'msg' => 'Метод не поддерживается'];
        }

        if (!CModule::IncludeModule("form")) {
            return ['success' => false, 'msg' => 'Модуль form не подключен'];
        }

        if (!CModule::IncludeModule("iblock")) {
            return ['success' => false, 'msg' => 'Модуль iblock не подключен'];
        }

		$ret = captchaValidation();

		if (is_array($ret)){
			return $ret;
		}

        // Получить название кластера по ID
        $clusterName = '';
        if (!empty($_POST['cluster'])) {
            $section = \CIBlockSection::GetByID($_POST['cluster'])->GetNext();
            if ($section) {
                $clusterName = $section['NAME'];
            }
        }

        // Получить название комнаты по ID
        $roomName = '';
        if (!empty($_POST['room'])) {
            $element = \CIBlockElement::GetByID($_POST['room'])->GetNext();
            if ($element) {
                $roomName = $element['NAME'];
            }
        }

        $arFields = array(
            "form_text_30" => $clusterName,
            "form_text_29" => $roomName,
            "form_text_31" => $_POST['last_name'] ?? '',
            "form_text_32" => $_POST['first_name'] ?? '',
            "form_text_33" => $_POST['middle_name'] ?? '',
            "form_email_34" => $_POST['email'] ?? '',
            "form_text_35" => $_POST['phone'] ?? '',
            "form_textarea_36" => $_POST['project_description'] ?? '',
            "form_text_37" => $_POST['company_name'] ?? '',

            "form_text_38" => $_POST['datetime_start'] ?? '',
            "form_text_39" => $_POST['datetime_end'] ?? '',


        );

        $RESULT_ID = CFormResult::Add(4, $arFields);

        if ($RESULT_ID) {
            CFormResult::Mail($RESULT_ID);
            return ['success' => true, 'msg' => $RESULT_ID];
        } else {
			global $strError;
            return ['success' => false, 'msg' => 'Ошибка при сохранении результата формы: '.$strError];
        }
    }
    catch (Exception $e) {
        return ['success' => false, 'msg' => $e->getMessage()];
    }

}
function getResidentMessages(){

}
function reportForm(){
    try {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return ['success' => false, 'msg' => 'Метод не поддерживается'];
        }

        if (!CModule::IncludeModule("form")) {
            return ['success' => false, 'msg' => 'Модуль form не подключен'];
        }

        if (!CModule::IncludeModule("iblock")) {
            return ['success' => false, 'msg' => 'Модуль iblock не подключен'];
        }

      /*  $ret = captchaValidation();

        if (is_array($ret)){
            return $ret;
        }
*/

        global $USER;
        $userId = $USER->GetID();
        $isEdit = isset($_POST['Id']) && is_numeric($_POST['Id']);
        $reportId = $isEdit ? intval($_POST['Id']) : 0;

        // Проверяем доступ к редактированию
        if ($isEdit) {
            $arResultData = [];
            $arAnswer2 = [];
            if (!CFormResult::GetDataByID($reportId, [], $arResultData, $arAnswer2) ||
                !isset($arAnswer2['SENDER_ID']['84']['USER_TEXT']) ||
                $arAnswer2['SENDER_ID']['84']['USER_TEXT'] != $userId) {
                return ['success' => false, 'msg' => 'Доступ запрещен'];
            }
        }

        // Расчет отклонений

        $revenue_dev = floatval($_POST['revenue_dev'] ?? 0);

        $invest_infra_dev = floatval($_POST['invest_infra_dev'] ?? 0);

        $invest_rnd_dev = floatval($_POST['invest_rnd_dev'] ?? 0);

        $employees_dev = floatval($_POST['employees_dev'] ?? 0);
        $export_share_dev = floatval($_POST['export_share_dev'] ?? 0);

        // Обработка вложений
        $fileIds = [];
        if (isset($_FILES['reportAttachments']) && is_array($_FILES['reportAttachments']['error'])) {
            foreach ($_FILES['reportAttachments']['name'] as $key => $name) {
                if ($_FILES['reportAttachments']['error'][$key] === UPLOAD_ERR_OK) {
                    $fileArray = CFile::MakeFileArray($_FILES['reportAttachments']['tmp_name'][$key]);
                    $fileArray['name'] = $name;
                    $fileArray['type'] = $_FILES['reportAttachments']['type'][$key];

                    $fileId = CFile::SaveFile($fileArray, "upload/tmp/"); // "my_folder" - имя папки для хранения файлов

                    if ($fileId > 0) {
                        $fileIds[] = $fileArray;
                    }
                }
            }
        }

        $arFields = array(

            "form_text_55" => $_POST['companyName'] ?? '',
            "form_text_56" => $_POST['reportYear'] ?? '',
            "form_text_58" => $_POST['revenue_plan'] ?? '',
            "form_text_57" => $_POST['revenue_fact'] ?? '',
            "form_text_77" => $revenue_dev, //
            "form_text_59" => $_POST['invest_infra_plan'] ?? '',
            "form_text_60" => $_POST['invest_infra_fact'] ?? '',
            "form_text_76" => $invest_infra_dev,

            "form_text_62" => $_POST['invest_rnd_plan'] ?? '',
            "form_text_61" => $_POST['invest_rnd_fact'] ?? '',
            "form_text_75" => $invest_rnd_dev,

            "form_text_63" => $_POST['employees_plan'] ?? '',
            "form_text_64" => $_POST['employees_fact'] ?? '',
            "form_text_74" => $employees_dev,

            "form_text_66" => $_POST['export_share_plan'] ?? '',
            "form_text_65" => $_POST['export_share_fact'] ?? '',
            "form_text_73" => $export_share_dev,


            "form_text_67" => $_POST['comment'] ?? '',
            "form_text_68" => $_POST['projectNames'] ?? '',

            "form_text_69" => $_POST['projectReadiness'] ?? '',
            "form_text_70" => $_POST['rids'] ?? '',

            "form_text_71" => $_POST['commercialization'] ?? '',
            "form_text_72" => $_POST['stateSupport'] ?? '',
            "form_text_84" =>  $userId

        );
        // удаляем старые файлы
        $fileFieldIds = [85, 86, 87, 88, 89, 90, 91, 92, 93, 94, 95, 96, 97, 103, 104];
        if(count($fileIds)>0) {
            foreach ($fileFieldIds as $fieldId) {
                $arFields["form_file_" . $fieldId] = ["del" => "Y"];
            }
        }

        foreach ($fileIds as $index => $fileId) {
            if ($index < count($fileFieldIds)) {
                $arFields["form_file_" . $fileFieldIds[$index]] = $fileId;
            }
        }

        if ($isEdit) {
            // Обновление существующего отчёта
            $RESULT_ID = CFormResult::Update($reportId, $arFields);
            if ($RESULT_ID) {
                return ['success' => true, 'msg' => $reportId, 'updated' => true];
            } else {
                global $strError;
                return ['success' => false, 'msg' => 'Ошибка при обновлении результата формы: '.$strError];
            }
        } else {
            // Создание нового отчёта
            $RESULT_ID = CFormResult::Add(6, $arFields);
            if ($RESULT_ID) {
                CFormResult::Mail($RESULT_ID);
                return ['success' => true, 'msg' => $RESULT_ID];
            } else {
                global $strError;
                return ['success' => false, 'msg' => 'Ошибка при сохранении результата формы: '.$strError];
            }
        }
    }
    catch (Exception $e) {
        return ['success' => false, 'msg' => $e->getMessage()];
    }

}
function educationPermission(){
    try {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return ['success' => false, 'msg' => 'Метод не поддерживается'];
        }

        if (!CModule::IncludeModule("form")) {
            return ['success' => false, 'msg' => 'Модуль form не подключен'];
        }

		$ret = captchaValidation();

		if (is_array($ret)){
			return $ret;
		}
        $arFields = [
            "form_text_40" => $_POST['full_name'] ?? '',
            "form_text_41" => $_POST['legal_form'] ?? '',
            "form_text_42" => $_POST['ogrn'] ?? '',
            "form_text_43" => $_POST['last_name'] ?? '',
            "form_text_44" => $_POST['first_name'] ?? '',
            "form_text_45" => $_POST['middle_name'] ?? '',
            "form_email_46" => $_POST['email'] ?? '',
            "form_text_47" => $_POST['phone'] ?? '',
            "form_textarea_48" => $_POST['program_description'] ?? '',
			"form_text_49" => $_POST['short_name'] ?? '',
			"form_text_50" => $_POST['address'] ?? '',
            "form_text_51" => $_POST['registration_date'] ?? '',
            "form_text_52" => $_POST['registration_authority'] ?? '',
            "form_text_53" => $_POST['education_types'] ?? '',
            "form_text_54" => $_POST['implementation_form'] ?? '',

        ];

        $RESULT_ID = CFormResult::Add(5, $arFields);

        if ($RESULT_ID) {
            CFormResult::Mail($RESULT_ID);
            return ['success' => true, 'msg' => $RESULT_ID];
        } else {
			global $strError;
            return ['success' => false, 'msg' => 'Ошибка при сохранении результата формы: '.$strError];
        }
    }
    catch (Exception $e) {
        return ['success' => false, 'msg' => $e->getMessage()];
    }
}

function captchaValidation() {
		if (@trim($_POST['token'])==''){
			return ['success' => false, 'msg' => 'Токен для captcha не найден'];
		}

		$ret = checkCaptcha($_POST['token']);
		if (!is_array($ret) || $ret['success']!=true || floatval($ret['score'])<0.5) {
			return ['success' => false, 'msg' => 'Вы не человек'];
		}
		return true;
}

function getFileTypeCustom($fileName) {
    $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    switch ($ext) {
        case 'pdf':
            return 'PDF';
        case 'doc':
            return 'DOC';
        case 'docx':
            return 'DOCX';
        default:
            return 'FILE';
    }
}

function formatSizeCustom($bytes) {
    if ($bytes >= 1073741824) {
        $bytes = number_format($bytes / 1073741824, 2) . ' GB';
    } elseif ($bytes >= 1048576) {
        $bytes = number_format($bytes / 1048576, 2) . ' MB';
    } elseif ($bytes >= 1024) {
        $bytes = number_format($bytes / 1024, 2) . ' KB';
    } elseif ($bytes > 1) {
        $bytes = $bytes . ' bytes';
    } elseif ($bytes == 1) {
        $bytes = $bytes . ' byte';
    } else {
        $bytes = '0 bytes';
    }
    return $bytes;
}

function searchResults()
{
    if (!CModule::IncludeModule("iblock")) {
        return array('success' => false, "msg" => "Модуль iblock не подключен");
    }
    $iblockId = 11;
    $arSelect = array("ID", "NAME", "PREVIEW_TEXT", "PROPERTY_FILE");
    $arFilter = array("IBLOCK_ID" => $iblockId, "ACTIVE" => "Y");
    if (isset($_GET['q']) && !empty($_GET['q'])) {
        $q = trim($_GET['q']);
        $arFilter[] = array(
            "LOGIC" => "OR",
            array("NAME" => "%" . $q . "%"),
            array("PREVIEW_TEXT" => "%" . $q . "%")
        );
    } else {
        return array();
    }
    $rsElements = CIBlockElement::GetList(array(), $arFilter, false, false, $arSelect);
    $result = array();
    while ($element = $rsElements->GetNextElement()) {
        $arFields = $element->GetFields();
        $fileId = $arFields["PROPERTY_FILE_VALUE"];
        if (!$fileId) {
            continue; // Если файла нет, пропускаем элемент
        }
        $file = CFile::GetFileArray($fileId);
        if (!$file) {
            continue;
        }
        $fileFormat = strtoupper(pathinfo($file['SRC'], PATHINFO_EXTENSION));
        $fileSize = round($file['FILE_SIZE'] / 1048576, 2) . ' MB';
        $result[] = array(
            "documentName" => $arFields["NAME"],
            "documentTitle" => $arFields["PREVIEW_TEXT"],
            "fileUrl" => $file['SRC'],
            "fileFormat" => $fileFormat,
            "fileSize" => $fileSize
        );
    }
    return $result;
}

function getSections($iblock_id, $parent_code='')
{
	Loader::includeModule('iblock');
	$parent_id = 0;
	if (!empty($parent_code)){
		$result = SectionTable::getList([
			'select' => ['ID'],
			'filter' => ['CODE' => $parent_code, 'ACTIVE' => 'Y', 'IBLOCK_ID'=>$iblock_id], // Filter by ID
			'limit' => 1 // Only get one result
		]);

		if ($section = $result->fetch()) {
			$parent_id = $section['ID'];
		}
	}
	$sections = [];
	$rsSections = SectionTable::getList([
		'filter' => [
			'IBLOCK_ID' => $iblock_id,
			'IBLOCK_SECTION_ID' => $parent_id,
			'ACTIVE' => 'Y',
		],
		'select' => ['ID'],
		'order' => ['SORT' => 'ASC'],
	]);
	while ($arSection = $rsSections->fetch()) {
		$sections[] = $arSection['ID'];
	}
	return $sections;
}

function getFullSections($iblock_id, $parent_code='')
{
	Loader::includeModule('iblock');
	$parent_id = 0;
	if (!empty($parent_code)){
		$result = SectionTable::getList([
			'select' => ['ID'],
			'filter' => ['CODE' => $parent_code, 'ACTIVE' => 'Y', 'IBLOCK_ID'=>$iblock_id], // Filter by ID
			'limit' => 1 // Only get one result
		]);

		if ($section = $result->fetch()) {
			$parent_id = $section['ID'];
		}
	}
	$sections = [];
	$rsSections = SectionTable::getList([
		'filter' => [
			'IBLOCK_ID' => $iblock_id,
			'IBLOCK_SECTION_ID' => $parent_id,
			'ACTIVE' => 'Y',
		],
		'select' => ['ID', 'NAME'],
		'order' => ['SORT' => 'ASC'],
	]);
	while ($arSection = $rsSections->fetch()) {
		$sections[$arSection['ID']] = $arSection;
	}
	return $sections;
}
function getElements($iblockId, array $arFilter=[], array $arSelect=["ID","NAME"])
{
    if (!CModule::IncludeModule("iblock")) {
        return [];
    }
	$arFilter['IBLOCK_ID'] = $iblockId;
    $rsElements = CIBlockElement::GetList(array(), $arFilter, false, false, $arSelect);
	$ret = [];
    while ($element = $rsElements->GetNextElement()) {
		$arFields = $element->GetFields();
        $ret[] = $arFields;
    }
    return $ret;
}

function getRussianMonth($monthNum) {
    $months = [
        '01' => 'января',
        '02' => 'февраля',
        '03' => 'марта',
        '04' => 'апреля',
        '05' => 'мая',
        '06' => 'июня',
        '07' => 'июля',
        '08' => 'августа',
        '09' => 'сентября',
        '10' => 'октября',
        '11' => 'ноября',
        '12' => 'декабря'
    ];
    return $months[$monthNum] ?? '';
}

function getRussianWeekday($weekdayNum) {
    $weekdays = [
        '0' => 'воскресенье',
        '1' => 'понедельник',
        '2' => 'вторник',
        '3' => 'среда',
        '4' => 'четверг',
        '5' => 'пятница',
        '6' => 'суббота'
    ];
    return $weekdays[$weekdayNum] ?? '';
}


function isManager() {
    global $USER;
    if (!$USER->IsAuthorized()) {
        return false;
    }
	$grps = $USER->GetUserGroupArray();
	return in_array(1, $grps) || in_array(6, $grps);
}

function isManagerr() {
    global $USER;
    if (!$USER->IsAuthorized()) {
        return false;
    }
	$grps = $USER->GetUserGroupArray();
	return in_array(1, $grps) || in_array(8, $grps);
}

function isManagerp() {
    global $USER;
    if (!$USER->IsAuthorized()) {
        return false;
    }
	$grps = $USER->GetUserGroupArray();
	return in_array(1, $grps) || in_array(9, $grps);
}

function isManagera() {
    global $USER;
    if (!$USER->IsAuthorized()) {
        return false;
    }
	$grps = $USER->GetUserGroupArray();
	return in_array(1, $grps) || in_array(10, $grps);
}

function isResident() {
    global $USER;
    if (!$USER->IsAuthorized()) {
        return false;
    }
	$grps = $USER->GetUserGroupArray();
	return in_array(7, $grps);
}
function getReportForm(){
    try {
        global $USER;
        if (!$USER->IsAuthorized()) {
            return ['success' => false, 'msg' => 'Пользователь не авторизован'];
        }

        if (!CModule::IncludeModule("form")) {
            return ['success' => false, 'msg' => 'Модуль form не подключен'];
        }

        if (!CModule::IncludeModule("iblock")) {
            return ['success' => false, 'msg' => 'Модуль iblock не подключен'];
        }

        $reportId = isset($_GET['Id']) ? intval($_GET['Id']) : 0;
        if (!$reportId) {
            return ['success' => false, 'msg' => 'ID отчета не указан'];
        }

        $userId = $USER->GetID();
        $arResultData = [];
        $arAnswer2 = [];

        if (!CFormResult::GetDataByID($reportId, [], $arResultData, $arAnswer2)) {
            return ['success' => false, 'msg' => 'Отчет не найден'];
        }

        // Проверяем, что отчет принадлежит текущему пользователю
        if (!isManager() && (!isset($arAnswer2['SENDER_ID']['84']['USER_TEXT']) || $arAnswer2['SENDER_ID']['84']['USER_TEXT'] != $userId)) {
            return ['success' => false, 'msg' => 'Доступ запрещен'];
        }

        // Извлекаем данные из формы
        $reportData = [
            'companyName' => $arAnswer2['COMPANY_NAME']['55']['USER_TEXT'] ?? '',
            'reportYear' => $arAnswer2['GOD']['56']['USER_TEXT'] ?? '',
            'metrics' => [
                'revenue' => [
                    'plan' => $arAnswer2['VYRUCHKA_PLAN']['58']['USER_TEXT'] ?? '',
                    'fact' => $arAnswer2['VYRUCHKA_FAKT']['57']['USER_TEXT'] ?? '',
                ],
                'invest_infra' => [
                    'plan' => $arAnswer2['INVESTICII_PLAN']['59']['USER_TEXT'] ?? '',
                    'fact' => $arAnswer2['INVESTICII_FAKT']['60']['USER_TEXT'] ?? '',
                ],
                'invest_rnd' => [
                    'plan' => $arAnswer2['INVESTICII_NIOKR_PLAN']['62']['USER_TEXT'] ?? '',
                    'fact' => $arAnswer2['INVESTICII_NIOKR_FAKT']['61']['USER_TEXT'] ?? '',
                ],
                'employees' => [
                    'plan' => $arAnswer2['CHISLENNOST_PLAN']['63']['USER_TEXT'] ?? '',
                    'fact' => $arAnswer2['CHISLENNOST_FAKT']['64']['USER_TEXT'] ?? '',
                ],
                'export_share' => [
                    'plan' => $arAnswer2['EXPORT_PLAN']['66']['USER_TEXT'] ?? '',
                    'fact' => $arAnswer2['EXPORT_FAKT']['65']['USER_TEXT'] ?? '',
                ],
            ],
            'revenue_dev' => $arAnswer2['VYRUCHKA_DEV']['77']['USER_TEXT'] ?? '',
            'invest_infra_dev' => $arAnswer2['INVESTICII_DEV']['76']['USER_TEXT'] ?? '',
            'invest_rnd_dev' => $arAnswer2['INVESTICII_NIOKR_DEV']['75']['USER_TEXT'] ?? '',
            'employees_dev' => $arAnswer2['CHISLENNOST_DEV']['74']['USER_TEXT'] ?? '',
            'export_share_dev' => $arAnswer2['EXPORT_DEV']['73']['USER_TEXT'] ?? '',
            'comment' => $arAnswer2['COMMENT']['67']['USER_TEXT'] ?? '',
            'projectNames' => $arAnswer2['PROJECT']['68']['USER_TEXT'] ?? '',
            'projectReadiness' => $arAnswer2['STADIA']['69']['USER_TEXT'] ?? '',
            'rids' => $arAnswer2['PERECHEN']['70']['USER_TEXT'] ?? '',
            'commercialization' => $arAnswer2['NTD']['71']['USER_TEXT'] ?? '',
            'stateSupport' => $arAnswer2['PODDERJKA']['72']['USER_TEXT'] ?? '',
        ];

        // Обработка вложений
        $attachments = [];
        $fileFieldIds = [85, 86, 87, 88, 89, 90, 91, 92, 93, 94, 95, 96, 97, 103, 104];
        foreach ($fileFieldIds as $fieldId) {
            if (isset($arAnswer2['FILE_IDS'][$fieldId]['USER_FILE_ID']) && $arAnswer2['FILE_IDS'][$fieldId]['USER_FILE_ID']) {
                $file = CFile::GetFileArray($arAnswer2['FILE_IDS'][$fieldId]['USER_FILE_ID']);
                if ($file) {
                    $attachments[] = [
                        'id' => $arAnswer2['FILE_IDS'][$fieldId]['USER_FILE_ID'],
                        'name' => $file['ORIGINAL_NAME'] ?: $file['FILE_NAME'],
                        'url' => $file['SRC'],
                        'size' => round($file['FILE_SIZE'] / 1048576, 2) . ' MB'
                    ];
                }
            }
        }
        $reportData['attachments'] = $attachments;

        return ['success' => true, 'data' => $reportData];
    }
    catch (Exception $e) {
        return ['success' => false, 'msg' => $e->getMessage()];
    }
}
function getChatData(){
	$isRes = isResident();
	$isMan = isManager();
	if (!$isRes && !$isMan){
		return [];
	}
	
	if ($isRes){
		global $USER;
		$userId = $USER->GetID();
	} else{
		$userId = @intval($_REQUEST['residentId']);
	}
	return getChatDataByUser($userId);
}

function getChatDataByUser($userId){
    if (!CModule::IncludeModule("highloadblock")) {
        return [];
    }

    $hlblock = Bitrix\Highloadblock\HighloadBlockTable::getById(1)->fetch();
    if (!$hlblock) {
        return [];
    }

    $entity = Bitrix\Highloadblock\HighloadBlockTable::compileEntity($hlblock);
    $entityDataClass = $entity->getDataClass();

	$flt = [];
	if ($userId>0){
		$flt['UF_RESIDENT'] = $userId;
	}

    $result = $entityDataClass::getList([
        'select' => ['ID', 'UF_AUTHOR', 'UF_SUBJECT', 'UF_MESSAGE', 'UF_CREATED_AT', 'UF_STATUS', 'UF_PARENT_ID',  'UF_RESIDENT'],
        'filter' => $flt,
		'group' => 'UF_TOPIC',
        'order' => ['UF_CREATED_AT' => 'DESC']
    ]);

    $messages = [];
    $replies = [];
    $authors = [];
	$residents = [];

	$allResidents = getElements(4, ["ACTIVE" => "Y"], ["ID", "NAME"]);
	foreach($allResidents as $item){
		$residents[$item['ID']] = $item['NAME'];
	}

    while ($row = $result->fetch()) {
        $authors[] = $row['UF_AUTHOR'];
        $item = [
            'id' => (int)$row['ID'],
            'createdAt' => $row['UF_CREATED_AT'] ? $row['UF_CREATED_AT']->format('Y-m-d\TH:i:s') : '',
            'subject' => $row['UF_SUBJECT'] ?: '',
            'message' => $row['UF_MESSAGE'] ?: '',
            'status' => $row['UF_STATUS'] ?: 'new',
            'author_id' => (int)$row['UF_AUTHOR'],
			'resident_id' => (int)$row['UF_RESIDENT'],
        ];

        if ($row['UF_PARENT_ID'] > 0) {
            $item['parent'] = (int)$row['UF_PARENT_ID'];
            $replies[] = $item;
        } else {
            $item['replies'] = [];
            $messages[] = $item;
        }
    }

    // Получаем emails авторов
    $authors = array_unique($authors);
    $logins = [];
    foreach ($authors as $authorId) {
        $userData = CUser::GetByID($authorId)->Fetch();
        $logins[$authorId] = $userData['LOGIN'] ?: '';
    }

    // Заполняем from
    foreach ($messages as &$message) {
        $message['from'] = $logins[$message['author_id']] ?: '';
		$message['to'] = $residents[$message['resident_id']] ?: '';
    }
    foreach ($replies as &$reply) {
        $reply['from'] = $residents[$reply['resident_id']] ?: '';
		$reply['to'] = $logins[$reply['author_id']] ?: '';
    }

    // Собираем replies для каждого message
    foreach ($messages as &$message) {
        $messageId = $message['id'];
        $message['replies'] = array_filter(array_map(function($reply) use ($messageId) {
            return $reply['parent'] == $messageId ? $reply : null;
        }, $replies));
    }
	return ['success' => true, 'data' => ['messages' => $messages]];
    //return array_merge($messages, $replies);
}
function getLoggedResident() {
	global $USER;
    $userId = $USER->GetID();
	$userFields = CUser::GetByID($userId)->Fetch();
    return @intval($userFields['UF_RESIDENT_TO_ELEMENT']);
}

function replyNotificationForm(){
    global $USER;
    if (!isResident()) {
        return ['success' => false, 'msg' => 'Пользователь не авторизован'];
    }

    if (!CModule::IncludeModule("highloadblock")) {
        return ['success' => false, 'msg' => 'Модуль highloadblock не подключен'];
    }

    $hlblock = Bitrix\Highloadblock\HighloadBlockTable::getById(1)->fetch();
    if (!$hlblock) {
        return ['success' => false, 'msg' => 'HL Block не найден'];
    }

    $entity = Bitrix\Highloadblock\HighloadBlockTable::compileEntity($hlblock);
    $entityDataClass = $entity->getDataClass();

    $residentId = getLoggedResident();
	

    $parentId = (int)$_POST['parentId'];
    $subject = trim($_POST['subject']);

    $message = trim($_POST['message']);

    if (!$parentId) {
        return ['success' => false, 'msg' => 'Не указан ID родителя или текст ответа'];
    }

    // Проверяем доступ к родительскому сообщению
    $parentResult = $entityDataClass::getList([
        'select' => ['UF_RESIDENT', 'UF_AUTHOR'],
        'filter' => ['ID' => $parentId],
        'limit' => 1
    ]);

    if (!$parentRow = $parentResult->fetch()) {
        return ['success' => false, 'msg' => 'Родительское сообщение не найдено'];
    }

    if ($parentRow['UF_RESIDENT'] != $residentId) {
        return ['success' => false, 'msg' => 'Доступ запрещен'];
    }

    // Сохраняем файлы если есть
    $fileIds = [];
    $tempFiles = [];

    if (isset($_FILES['replyAttachments']) && is_array($_FILES['replyAttachments']['error'])) {
        foreach ($_FILES['replyAttachments']['name'] as $key => $name) {
            if($name) {
                if ($_FILES['replyAttachments']['error'][$key] === UPLOAD_ERR_OK) {
                    $fileArray = CFile::MakeFileArray($_FILES['replyAttachments']['tmp_name'][$key]);
                    $fileArray['name'] = $name; // Устанавливаем оригинальное имя файла
                    $fileArray['type'] = $_FILES['replyAttachments']['type'][$key]; // Устанавливаем тип файла

                    $fileId = CFile::SaveFile($fileArray, "upload/tmp/"); // "my_folder" - имя папки для хранения файлов

                    if ($fileId > 0) {
                        $fileIds[] = \CFile::MakeFileArray($fileId); // Добавляем ID файла в массив
                    } else {
                        echo "Ошибка загрузки файла: $name.";
                    }
                } else {
                    echo "Ошибка загрузки файла $name: " . $_FILES['replyAttachments']['error'][$key];
                }
            }
        }
    }

    // Добавляем запись
    $data = [
        'UF_AUTHOR' => $parentRow['UF_AUTHOR'],
        'UF_SUBJECT' => $subject ?: 'Re: ',
        'UF_MESSAGE' => $message,
        'UF_CREATED_AT' => new \Bitrix\Main\Type\DateTime(),
        'UF_STATUS' => 'new',
        'UF_PARENT_ID' => $parentId,
        'UF_RESIDENT' => $parentRow['UF_RESIDENT'],
    ];

    if (!empty($fileIds)) {
        $data['UF_FILE_IDS'] = $fileIds;
    }
    $addResult = $entityDataClass::add($data);

    if ($addResult->isSuccess()) {
        return ['success' => true, 'msg' => 'Ответ отправлен'];
    } else {
        return ['success' => false, 'msg' => 'Ошибка сохранения: ' . implode(', ', $addResult->getErrorMessages())];
    }
}
function processNotificationFiles($fileIds) {
    if (empty($fileIds)) {
        return [];
    }
    $files = [];
    if (!is_array($fileIds)) {
        $fileIds = [$fileIds];
    }
    foreach ($fileIds as $fileId) {
        $file = CFile::GetFileArray($fileId);
        if ($file) {
            $files[] = [
                'name' => $file['ORIGINAL_NAME'] ?: $file['FILE_NAME'],
                'url' => $file['SRC'],
                'size' => formatSizeCustom($file['FILE_SIZE']),
                'type' => getFileTypeCustom($file['FILE_NAME'])
            ];
        }
    }
    return $files;
}

function getNotificationContent(){
    global $USER;
    if (!$USER->IsAuthorized()) {
        return ['success' => false, 'msg' => 'Пользователь не авторизован'];
    }

    $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
    if (!$id) {
        return ['success' => false, 'msg' => 'ID сообщения не указан'];
    }

    if (!CModule::IncludeModule("highloadblock")) {
        return ['success' => false, 'msg' => 'Модуль highloadblock не подключен'];
    }

    $hlblock = Bitrix\Highloadblock\HighloadBlockTable::getById(1)->fetch();
    if (!$hlblock) {
        return ['success' => false, 'msg' => 'HL Block не найден'];
    }

    $entity = Bitrix\Highloadblock\HighloadBlockTable::compileEntity($hlblock);
    $entityDataClass = $entity->getDataClass();

    $userId = $USER->GetID();
    $userGroups = $USER->GetUserGroupArray();

    $result = $entityDataClass::getList([
        'select' => ['UF_MESSAGE', 'UF_RESIDENT', 'UF_FILE_IDS'],
        'filter' => ['ID' => $id],
        'limit' => 1
    ]);

    if ($row = $result->fetch()) {
        // Проверяем доступ: UF_RESIDENT совпадает или пользователь менеджер (группа 6)
        if ($row['UF_RESIDENT'] != $userId && !in_array(6, $userGroups)) {
            return ['success' => false, 'msg' => 'Доступ запрещен'];
        }

        return [
            'id' => $id,
            'body' => nl2br(htmlspecialchars($row['UF_MESSAGE'] ?: '')),
            'files' => processNotificationFiles($row['UF_FILE_IDS'])
        ];
    } else {
        return ['success' => false, 'msg' => 'Сообщение не найдено'];
    }
}
function markNotificationRead(){
    global $USER;
    if (!$USER->IsAuthorized()) {
        return ['success' => false, 'msg' => 'Пользователь не авторизован'];
    }

    $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
    if (!$id) {
        return ['success' => false, 'msg' => 'ID сообщения не указан'];
    }

    if (!CModule::IncludeModule("highloadblock")) {
        return ['success' => false, 'msg' => 'Модуль highloadblock не подключен'];
    }

    $hlblock = Bitrix\Highloadblock\HighloadBlockTable::getById(1)->fetch();
    if (!$hlblock) {
        return ['success' => false, 'msg' => 'HL Block не найден'];
    }

    $entity = Bitrix\Highloadblock\HighloadBlockTable::compileEntity($hlblock);
    $entityDataClass = $entity->getDataClass();


    // Проверяем доступ
    $checkResult = $entityDataClass::getList([
        'select' => ['UF_RESIDENT'],
        'filter' => ['ID' => $id],
        'limit' => 1
    ]);

    if ($row = $checkResult->fetch()) {
        if (isResident() && $row['UF_RESIDENT'] != getLoggedResident()) {
            return ['success' => false, 'msg' => 'Доступ запрещен'];
        }
    } else {
        return ['success' => false, 'msg' => 'Сообщение не найдено'];
    }

    // Обновляем статус
    $updateResult = $entityDataClass::update($id, ['UF_STATUS' => 'read']);

    if ($updateResult->isSuccess()) {
        return ['success' => true, 'msg' => 'Сообщение отмечено как прочитанное'];
    } else {
        return ['success' => false, 'msg' => 'Ошибка обновления: ' . implode(', ', $updateResult->getErrorMessages())];
    }
}

function addNotificationForm(){
    global $USER;
    if (!isManager()) {
        return ['success' => false, 'msg' => 'Доступ запрещен'];
    }

    if (!CModule::IncludeModule("highloadblock")) {
        return ['success' => false, 'msg' => 'Модуль highloadblock не подключен'];
    }

    $hlblock = Bitrix\Highloadblock\HighloadBlockTable::getById(1)->fetch();
    if (!$hlblock) {
        return ['success' => false, 'msg' => 'HL Block не найден'];
    }

    $entity = Bitrix\Highloadblock\HighloadBlockTable::compileEntity($hlblock);
    $entityDataClass = $entity->getDataClass();

    $userId = $USER->GetID();

	$resident = is_array($_POST['resident'])?(array)$_POST['resident']:[];
	if (empty($resident)){
		$allResidents = getElements(4, ["ACTIVE" => "Y"], ["ID", "NAME"]);
		foreach($allResidents as $item){
			$resident[] = $item['ID'];
		}
	}
    $subject = trim($_POST['subject']);
    $message = trim($_POST['message']);

	$topic = time();
	$ret = [];
	foreach($resident as $row){
    // Добавляем запись
		$data = [
			'UF_AUTHOR' => $userId,
			'UF_SUBJECT' => $subject,
			'UF_MESSAGE' => $message,
			'UF_CREATED_AT' => new \Bitrix\Main\Type\DateTime(),
			'UF_STATUS' => 'new',
			'UF_TOPIC' => $topic,
			'UF_RESIDENT' => $row['ID'],
		];

		$addResult = $entityDataClass::add($data);
		if (!$addResult->isSuccess()){
			$ret[] = [$row['ID']=>$addResult->getErrorMessages()];
		}
	}
	if (!empty($ret)){
		return ['success' => false, 'msg' => 'Ошибки отправки', 'error'=>$ret];
	}
	return ['success' => true, 'msg' => 'Сообщение отправлено'];
}

function personalFormSave() {
    try {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return ['success' => false, 'msg' => 'Метод не поддерживается'];
        }

        if (!CModule::IncludeModule("iblock")) {
            return ['success' => false, 'msg' => 'Модуль iblock не подключен'];
        }

        global $USER;
        if (!$USER->IsAuthorized()) {
            return ['success' => false, 'msg' => 'Пользователь не авторизован'];
        }

        $userId = $USER->GetID();
        $userFields = CUser::GetByID($userId)->Fetch();
        $elementId = $userFields['UF_RESIDENT_TO_ELEMENT'];

        if (!$elementId) {
            return ['success' => false, 'msg' => 'Элемент резидента не найден'];
        }

        // Обновление элемента инфоблока
        $el = new CIBlockElement;

        $arFields = array(
            "NAME" => $_POST['company_name'] ?? '',
            "PREVIEW_TEXT" => $_POST['project_description'] ?? '',
        );

        // Свойства
        $arProps = array(
            "WEBSITE" => $_POST['company_link'] ?? '',
        );

        // Логотип
        if (isset($_FILES['file']) && $_FILES['file']['error'] == UPLOAD_ERR_OK) {
            $fileId = CFile::SaveFile($_FILES['file'], "resident_logos");
            if ($fileId) {
                $arProps["IMAGE"] = $fileId;
            }
        }

        $arFields["PROPERTY_VALUES"] = $arProps;

        $res = $el->Update($elementId, $arFields);
        if (!$res) {
            return ['success' => false, 'msg' => 'Ошибка обновления элемента: ' . $el->LAST_ERROR];
        }

        // Получение URL логотипа
        $elementObj = CIBlockElement::GetByID($elementId);
        if ($elementObj) {
            $element = $elementObj->GetNextElement();
            $arProps = $element->GetProperties();
            $imageFileId = $arProps['IMAGE']['VALUE'];
            $imageUrl = "";
            if ($imageFileId) {
                $imageFile = CFile::GetFileArray($imageFileId);
                $imageUrl = $imageFile ? $imageFile["SRC"] : "";
            }
        }

        // Обновление пользователя
        $user = new CUser;
        $arUserFields = array(
            "LAST_NAME" => $_POST['last_name'] ?? '',
            "NAME" => $_POST['first_name'] ?? '',
            "SECOND_NAME" => $_POST['middle_name'] ?? '',
            "WORK_POSITION" => $_POST['position'] ?? '',
            "EMAIL" => $_POST['email'] ?? '',
            "PERSONAL_PHONE" => $_POST['phone'] ?? '',
        );

        $userRes = $user->Update($userId, $arUserFields);
        if (!$userRes) {
            return ['success' => false, 'msg' => 'Ошибка обновления пользователя: ' . $user->LAST_ERROR];
        }

        // Отправка уведомления менеджерам (группа 6)
        $arFilter = array("GROUPS_ID" => array(6), "ACTIVE" => "Y");
        $rsUsers = CUser::GetList('', '', $arFilter);
        while($arUser = $rsUsers->Fetch()) {
            $arEventFields = array(
                "EMAIL" => $arUser["EMAIL"],
                "RESIDENT_NAME" => trim(($userFields["LAST_NAME"] ?? "") . " " . ($userFields["NAME"] ?? "") . " " . ($userFields["SECOND_NAME"] ?? "")),
                "RESIDENT_EMAIL" => $userFields["EMAIL"],
                "COMPANY_NAME" => $_POST['company_name'] ?? '',
                "COMPANY_LINK" => $_POST['company_link'] ?? '',
                "DOLG" => $_POST['position'] ?? '',
                "PROJECT_DESCRIPTION" => $_POST['project_description'] ?? '',
                "RESIDENT_ELEMENT_ID" => $elementId,
                "IMAGE_URL" => $imageUrl,
                "PERSONAL_PHONE" => $_POST['phone'],
                "SAVE_DATE" => date("d.m.Y H:i:s"),
            );
            CEvent::Send("RESIDENT_FORM_SAVE", "s1", $arEventFields);
        }

        return ['success' => true, 'msg' => 'Данные успешно сохранены'];

    } catch (Exception $e) {
        return ['success' => false, 'msg' => $e->getMessage()];
    }
}

function custom_mail($to, $subject, $message, $additional_headers, $additional_parameters, $context){
	return @mail($to, $subject, $message);
}
