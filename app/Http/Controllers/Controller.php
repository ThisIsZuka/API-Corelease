<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;



/**
 * @OA\Info(
 *      version="1.2.0",
 *      title="L5 OpenApi API-Corelease",
 *      description="L5 Swagger OpenApi description"
 * )
 *
 * 
 * 
 * @OA\Post(
 * path="/api/Get_Token",
 * summary="Login",
 * description="Get_Token",
 * operationId="Get_Token",
 * tags={"Authentication"},
 *   @OA\RequestBody(
 *      required=true,
 *      description="Customer information",
 *      @OA\JsonContent(
 *         required={"username","password"},
 *         @OA\Property(property="username", type="string", format="string", example="api_ufund"),
 *         @OA\Property(property="password", type="string", format="password", example="U6undp0Rt4l"),
 *      ),
 *   ),
 * @OA\Response(
 *    response=422,
 *    description="Wrong credentials response",
 *    @OA\JsonContent(
 *       @OA\Property(property="Code", type="string", example="9999"),
 *       @OA\Property(property="status", type="string", example="Sucsess"),
 *       @OA\Property(property="data", type="string", example="[...]"),
 *        )
 *     )
 * ),
 * 
 * 
 *
 * 
 * @OA\Get(
 * path="/api/master_prefix",
 * summary="Master Data คำนำหน้าชื่อ",
 * description="คำนำหน้าชื่อ",
 * operationId="Master_prefix",
 * tags={"API Personal Information"},
 * @OA\Response(
 *    response=422,
 *    description="Wrong credentials response",
 *    @OA\JsonContent(
 *       @OA\Property(property="Code", type="string", example="9999"),
 *       @OA\Property(property="status", type="string", example="Sucsess"),
 *       @OA\Property(property="data", type="string", example="[...]"),
 *        )
 *     )
 * ),
 * 
 * @OA\Get(
 * path="/api/master_nationality",
 * summary="Master Data สัญชาติ",
 * description="สัญชาติ",
 * operationId="master_nationality",
 * tags={"API Personal Information"},
 * @OA\Response(
 *    response=422,
 *    description="Wrong credentials response",
 *    @OA\JsonContent(
 *       @OA\Property(property="Code", type="string", example="9999"),
 *       @OA\Property(property="status", type="string", example="Sucsess"),
 *       @OA\Property(property="data", type="string", example="[...]"),
 *        )
 *     )
 * ),
 * 
 * @OA\Get(
 * path="/api/master_marital_status",
 * summary="Master Data สถานะสมรส",
 * description="สถานะสมรส",
 * operationId="master_marital_status",
 * tags={"API Personal Information"},
 * @OA\Response(
 *    response=422,
 *    description="Wrong credentials response",
 *    @OA\JsonContent(
 *       @OA\Property(property="Code", type="string", example="9999"),
 *       @OA\Property(property="status", type="string", example="Sucsess"),
 *       @OA\Property(property="data", type="string", example="[...]"),
 *        )
 *     )
 * ),
 * 
 * @OA\Get(
 * path="/api/master_occupation",
 * summary="Master Data อาชีพ",
 * description="อาชีพ",
 * operationId="master_occupation",
 * tags={"API Personal Information"},
 * @OA\Response(
 *    response=422,
 *    description="Wrong credentials response",
 *    @OA\JsonContent(
 *       @OA\Property(property="Code", type="string", example="9999"),
 *       @OA\Property(property="status", type="string", example="Sucsess"),
 *       @OA\Property(property="data", type="string", example="[...]"),
 *        )
 *     )
 * ),
 * 
 * @OA\Get(
 * path="/api/master_level_type",
 * summary="Master Data ระดับการศึกษา",
 * description="ระดับการศึกษา",
 * operationId="master_level_type",
 * tags={"API Personal Information"},
 * @OA\Response(
 *    response=422,
 *    description="Wrong credentials response",
 *    @OA\JsonContent(
 *       @OA\Property(property="Code", type="string", example="9999"),
 *       @OA\Property(property="status", type="string", example="Sucsess"),
 *       @OA\Property(property="data", type="string", example="[...]"),
 *        )
 *     )
 * ),
 * 
 * @OA\Get(
 * path="/api/master_level",
 * summary="Master Data ชั้นปี",
 * description="ชั้นปี",
 * operationId="master_level",
 * tags={"API Personal Information"},
 * @OA\Response(
 *    response=422,
 *    description="Wrong credentials response",
 *    @OA\JsonContent(
 *       @OA\Property(property="Code", type="string", example="9999"),
 *       @OA\Property(property="status", type="string", example="Sucsess"),
 *       @OA\Property(property="data", type="string", example="[...]"),
 *        )
 *     )
 * ),
 * 
 * @OA\Get(
 * path="/api/master_rerationship_ref",
 * summary="Master Data ความสัมพันธ์",
 * description="ความสัมพันธ์",
 * operationId="master_rerationship_ref",
 * tags={"API Personal Information"},
 * @OA\Response(
 *    response=422,
 *    description="Wrong credentials response",
 *    @OA\JsonContent(
 *       @OA\Property(property="Code", type="string", example="9999"),
 *       @OA\Property(property="status", type="string", example="Sucsess"),
 *       @OA\Property(property="data", type="string", example="[...]"),
 *        )
 *     )
 * ),
 * 
 * @OA\Get(
 * path="/api/master_branch_type",
 * summary="Master Data ประเภทสาขา",
 * description="ประเภทสาขา",
 * operationId="master_branch_type",
 * tags={"API Branch"},
 * @OA\Response(
 *    response=422,
 *    description="Wrong credentials response",
 *    @OA\JsonContent(
 *       @OA\Property(property="Code", type="string", example="9999"),
 *       @OA\Property(property="status", type="string", example="Sucsess"),
 *       @OA\Property(property="data", type="string", example="[...]"),
 *        )
 *     )
 * ),
 * 
 * @OA\Post(
 * path="/api/master_setup_company/{BRANCH_TYPE_ID}",
 * summary="Master Data สาขา",
 * description="สาขา",
 * operationId="master_setup_company",
 * tags={"API Branch"},
 * @OA\Parameter(
 *     name="BRANCH_TYPE_ID",
 *     in="path",
 *     description="รหัสประเภทสาขา",
 *     required=false,
 * ),
 * @OA\RequestBody(
 *    required=false,
 *    description="Search text",
 *    @OA\JsonContent(
 *       @OA\Property(property="Search", type="string", format="string", example="ขอนแก่น"),
 *    ),
 * ),
 * @OA\Response(
 *    response=422,
 *    description="Wrong credentials response",
 *    @OA\JsonContent(
 *       @OA\Property(property="Code", type="string", example="9999"),
 *       @OA\Property(property="status", type="string", example="Sucsess"),
 *       @OA\Property(property="data", type="string", example="[...]"),
 *        )
 *     )
 * ),
 * 
 * @OA\Get(
 * path="/api/SKU_GetProduct",
 * summary="สินค้า",
 * description="รายการสินค้าที่เปิด",
 * operationId="SKU_GetProduct",
 * tags={"API Product"},
 * @OA\Response(
 *    response=422,
 *    description="Wrong credentials response",
 *    @OA\JsonContent(
 *       @OA\Property(property="Code", type="string", example="9999"),
 *       @OA\Property(property="status", type="string", example="Sucsess"),
 *       @OA\Property(property="data", type="string", example="[...]"),
 *        )
 *     )
 * ),
 * 
 * @OA\Get(
 * path="/api/master_installment",
 * summary="Master Data จำนวนงวด",
 * description="จำนวนงวด",
 * operationId="master_installment",
 * tags={"API INSTALLMENT"},
 * @OA\Response(
 *    response=422,
 *    description="Wrong credentials response",
 *    @OA\JsonContent(
 *       @OA\Property(property="Code", type="string", example="9999"),
 *       @OA\Property(property="status", type="string", example="Sucsess"),
 *       @OA\Property(property="data", type="string", example="[...]"),
 *        )
 *     )
 * ),
 * 
 *
 * @OA\Get(
 * path="/api/master_residence",
 * summary="Master Data สถานะการอาศัย",
 * description="สถานะการอาศัย (OWNER_TYPE)",
 * operationId="master_residence",
 * tags={"API Address"},
 * @OA\Response(
 *    response=422,
 *    description="Wrong credentials response",
 *    @OA\JsonContent(
 *       @OA\Property(property="Code", type="string", example="9999"),
 *       @OA\Property(property="status", type="string", example="Sucsess"),
 *       @OA\Property(property="data", type="string", example="[...]"),
 *        )
 *     )
 * ),
 * 
 * 
 * @OA\Get(
 * path="/api/master_province",
 * summary="Master Data จังหวัด",
 * description="จังหวัด",
 * operationId="master_province",
 * tags={"API Address"},
 * @OA\Response(
 *    response=422,
 *    description="Wrong credentials response",
 *    @OA\JsonContent(
 *       @OA\Property(property="Code", type="string", example="9999"),
 *       @OA\Property(property="status", type="string", example="Sucsess"),
 *       @OA\Property(property="data", type="string", example="[...]"),
 *        )
 *     )
 * ),
 * 
 * 
 * @OA\Get(
 * path="/api/master_district/{PROVINCE_ID}",
 * summary="Master Data อำเภอ",
 * description="อำเภอ",
 * operationId="master_district",
 * tags={"API Address"},
 * @OA\Parameter(
 *     name="PROVINCE_ID",
 *     in="path",
 *     description="รหัสจังหวัด เช่น 10",
 *     required=false,
 * ),
 * @OA\Response(
 *    response=422,
 *    description="Wrong credentials response",
 *    @OA\JsonContent(
 *       @OA\Property(property="Code", type="string", example="9999"),
 *       @OA\Property(property="status", type="string", example="Sucsess"),
 *       @OA\Property(property="data", type="string", example="[...]"),
 *        )
 *     )
 * ),
 * 
 * @OA\Get(
 * path="/api/master_sub_district/{DISTRICT_ID}",
 * summary="Master Data ตำบล",
 * description="ตำบล",
 * operationId="master_sub_district",
 * tags={"API Address"},
 * @OA\Parameter(
 *     name="DISTRICT_ID",
 *     in="path",
 *     description="รหัสอำเภอ เช่น 1001",
 *     required=false,
 * ),
 * @OA\Response(
 *    response=422,
 *    description="Wrong credentials response",
 *    @OA\JsonContent(
 *       @OA\Property(property="Code", type="string", example="9999"),
 *       @OA\Property(property="status", type="string", example="Sucsess"),
 *       @OA\Property(property="data", type="string", example="[...]"),
 *        )
 *     )
 * ),
 * 
 * 
 * 
 * @OA\Post(
 * path="/api/master_university",
 * summary="Master Data มหาวิทยาลัย",
 * description="มหาวิทยาลัย",
 * operationId="Get-master_university",
 * tags={"API University"},
 * @OA\RequestBody(
 *    required=false,
 *    description="Customer information",
 *    @OA\JsonContent(
 *       @OA\Property(property="PROVINCE_ID", type="string", format="string", example="10"),
 *       @OA\Property(property="DISTRICT_ID", type="string", format="string", example="1007"),
 *       @OA\Property(property="U_Search", type="string", format="string", example="จุฬา"),
 *    ),
 * ),
 * @OA\Response(
 *    response=422,
 *    description="Wrong credentials response",
 *    @OA\JsonContent(
 *       @OA\Property(property="Code", type="string", example="9999"),
 *       @OA\Property(property="status", type="string", example="Sucsess"),
 *       @OA\Property(property="data", type="string", example="[...]"),
 *        )
 *     )
 * ),
 * 
 * 
 * @OA\Get(
 * path="/api/master_faculty",
 * summary="Master Data คณะ",
 * description="คณะ",
 * operationId="Get-master_faculty",
 * tags={"API University"},
 *  @OA\Parameter(
 *          name="MT_UNIVERSITY_ID",
 *          in="query",
 *          description="รหัสมหาวิทยาลัย เช่น 2",
 *          required=false,
 *   ),
 * @OA\Response(
 *    response=422,
 *    description="Wrong credentials response",
 *    @OA\JsonContent(
 *       @OA\Property(property="Code", type="string", example="9999"),
 *       @OA\Property(property="status", type="string", example="Sucsess"),
 *       @OA\Property(property="data", type="string", example="[...]"),
 *        )
 *     )
 * ),
 * 
 * 
 * 
 * 
 * @OA\SecurityScheme(
 *     type="apiKey",
 *     description="Login with username and password to get the authentication token",
 *     name="api-token",
 *     in="header",
 *     securityScheme="Api-Token",
 * ),
 * 
 * 
 * 
 * 
 * @OA\Post(
 * path="/api/new_Quotation",
 * summary="Register Quotation",
 * description="Regsiter Quotation",
 * operationId="new_Quotation",
 * tags={"API QUOTATION"},
 * security={{ "Api-Token": {} }},
 *   @OA\RequestBody(
 *      required=true,
 *      description="customer information",
 *      @OA\MediaType(
 *          mediaType="multipart/form-data",
 *          @OA\Schema(
 *              required={
 *                        "BRANCH_TYPE", "BRANCH_ID", "TAX_ID", "OCCUPATION_ID", "UNIVERSITY_ID", "FACULTY_ID", "PRODUCT_SERIES", "DOWN_SUM_AMT", "INSTALL_NUM",
 *                        "PREFIX", "FIRST_NAME", "LAST_NAME", "BIRTHDAY", "SEX", "MARITAL_STATUS", "PHONE", "EMAIL", 
 *                        "OCCUPATION_ID", "MAIN_INCOME", "LEVEL_TYPE", "U_LEVEL", "LOAN_KYS", "REF_TAX_ID", 
 *                        "REF_TITLE", "REF_FIRSTNAME", "REF_LASTNAME", "RELATION_REFERENCE", "REF_OCCUPATION", "REF_BIRTHDAY", "REF_PHONE", "IDCARD_FILE", "STUDENTCARD_FILE", 
 *                        "FACE_FILE", "STUDENT_ID", 
 *                        "A1_NO", "A1_PROVINCE", "A1_DISTRICT", "A1_SUBDISTRICT", "A1_POSTALCODE", "A1_OWNER_TYPE", "A2_NO", "A2_PROVINCE", "A2_DISTRICT", "A2_SUBDISTRICT", "A2_POSTALCODE", "A2_OWNER_TYPE", 
 *                        "A3_NO", "A3_PROVINCE", "A3_DISTRICT", "A3_SUBDISTRICT", "A3_POSTALCODE", "A3_OWNER_TYPE"
 *                       },
 *              @OA\Property(property="BRANCH_TYPE", type="string", format="string", example="1"),
 *              @OA\Property(property="BRANCH_ID", type="string", format="string", example="31"),
 *              @OA\Property(property="TAX_ID", type="string", format="string", example="4856465441239"),
 *              @OA\Property(property="OCCUPATION_ID", type="string", format="string", example="2"),
 *              @OA\Property(property="PRODUCT_SERIES", type="string", format="string", example="194252038338"),
 *              @OA\Property(property="DOWN_SUM_AMT", type="string", format="string", example="5415"),
 *              @OA\Property(property="INSTALL_NUM", type="string", format="string", example="24"),
 *              @OA\Property(property="ACS_ID", type="string", format="string", example="484"),
 *              @OA\Property(property="INSURE_ID", type="string", format="string", example="42"),
 * 
 *              @OA\Property(property="PREFIX", type="string", format="string", example="2"),
 *              @OA\Property(property="FIRST_NAME", type="string", format="string", example="ทดสอบ"),
 *              @OA\Property(property="LAST_NAME", type="string", format="string", example="เอพีไอ"),
 *              @OA\Property(property="STUDENT_ID", type="int", format="int", example="1122334455"),
 *              @OA\Property(property="BIRTHDAY", type="string", format="date", example="2544-10-18"),
 *              @OA\Property(property="SEX", type="string", format="string", example="1"),
 *              @OA\Property(property="MARITAL_STATUS", type="string", format="string", example="1"),
 *              @OA\Property(property="PHONE", type="string", format="string", example="0812345678"),
 *              @OA\Property(property="PHONE_SECOND", type="string", format="string", example="0876543219"),
 *              @OA\Property(property="EMAIL", type="string", format="email", example="test@hotmail.com"),
 *              @OA\Property(property="FACEBOOK", type="string", format="string"),
 *              @OA\Property(property="LINEID", type="string", format="string"),
 *              @OA\Property(property="MAIN_INCOME", type="int", format="int", example="5000"),
 *              @OA\Property(property="UNIVERSITY_ID", type="int", format="int", example="2"),
 *              @OA\Property(property="FACULTY_ID", type="int", format="int", example="47"),
 *              @OA\Property(property="FACULTY_OTHER", type="string", format="string"),
 *              @OA\Property(property="LEVEL_TYPE", type="string", format="string", example="1"),
 *              @OA\Property(property="U_LEVEL", type="int", format="int", example="2"),
 *              @OA\Property(property="LOAN_KYS", type="string", format="string", example="ํYES"),
 *              @OA\Property(property="REF_TAX_ID", type="int", format="int", example="1122233339310"),
 *              @OA\Property(property="REF_TITLE", type="string", format="string", example="1"),
 *              @OA\Property(property="REF_TITLE_OTHER", type="string", format="string"),
 *              @OA\Property(property="REF_FIRSTNAME", type="string", format="string", example="นายทดสอบ"),
 *              @OA\Property(property="REF_LASTNAME", type="string", format="string", example="สกุลทดสอบ"),
 *              @OA\Property(property="RELATION_REFERENCE", type="int", format="int", example="1"),
 *              @OA\Property(property="REF_OCCUPATION", type="int", format="int", example="49"),
 *              @OA\Property(property="REF_BIRTHDAY", type="string", format="date", example="2544-10-18"),
 *              @OA\Property(property="REF_PHONE", type="string", format="string", example="0876543211"),
 *              @OA\Property(property="REF_PHONE_SECOND", type="string", format="string", example="0800521521"),
 *              @OA\Property(property="IDCARD_FILE", type="file", format="binary" ),
 *              @OA\Property(property="STUDENTCARD_FILE", type="file", format="binary" ),
 *              @OA\Property(property="FACE_FILE", type="file", format="binary" ),
 *              @OA\Property(property="URLMAP", type="string", format="string", example="https://www.google.co.th/maps"),
 * 
 *              @OA\Property(property="EMAILGuarantor", type="string", format="string"),
 *              @OA\Property(property="A1_NO", type="string", format="string", example="17/51", description="บ้านเลขที่"),
 *              @OA\Property(property="A1_MOI", type="string", format="string", description="หมู่ที่"),
 *              @OA\Property(property="A1_VILLAGE", type="string", format="string", description="หมู่บ้าน/โครงการ"),
 *              @OA\Property(property="A1_BUILDING", type="string", format="string", description="อาคาร"),
 *              @OA\Property(property="A1_FLOOR", type="string", format="string", description="ชั่น"),
 *              @OA\Property(property="A1_ROOM_NO", type="string", format="string", description="เลขที่ห้อง"),
 *              @OA\Property(property="A1_SOI", type="string", format="string", description="ซอย"),
 *              @OA\Property(property="A1_ROAD", type="string", format="string", description="ถนน"),
 *              @OA\Property(property="A1_PROVINCE", type="string", format="string", example="10", description="จังหวัด"),
 *              @OA\Property(property="A1_DISTRICT", type="string", format="string", example="1003", description="อำเภอ"),
 *              @OA\Property(property="A1_SUBDISTRICT", type="string", format="string", example="100302", description="ตำบล"),
 *              @OA\Property(property="A1_POSTALCODE", type="string", format="string", example="10530", description="รหัสไปรษณี"),
 *              @OA\Property(property="A1_OWNER_TYPE", type="string", format="string", example="8", description="สถานะการพักอาศัย"),
 *              @OA\Property(property="A1_LIVEING_TIME", type="string", format="string", description="ระยะเวลาที่อาศัยอยู่"),
 *              @OA\Property(property="A1_PHONE", type="string", format="string", description="เบอร์โทรศัทพ์"),
 *              @OA\Property(property="A2_NO", type="string", format="string", example="17/51"),
 *              @OA\Property(property="A2_MOI", type="string", format="string"),
 *              @OA\Property(property="A2_VILLAGE", type="string", format="string"),
 *              @OA\Property(property="A2_BUILDING", type="string", format="string"),
 *              @OA\Property(property="A2_FLOOR", type="string", format="string"),
 *              @OA\Property(property="A2_ROOM_NO", type="string", format="string"),
 *              @OA\Property(property="A2_SOI", type="string", format="string"),
 *              @OA\Property(property="A2_ROAD", type="string", format="string"),
 *              @OA\Property(property="A2_PROVINCE", type="string", format="string", example="10"),
 *              @OA\Property(property="A2_DISTRICT", type="string", format="string", example="1003"),
 *              @OA\Property(property="A2_SUBDISTRICT", type="string", format="string", example="100302"),
 *              @OA\Property(property="A2_POSTALCODE", type="string", format="string", example="10530"),
 *              @OA\Property(property="A2_OWNER_TYPE", type="string", format="string", example="8"),
 *              @OA\Property(property="A2_LIVEING_TIME", type="string", format="string"),
 *              @OA\Property(property="A2_PHONE", type="string", format="string"),
 *              @OA\Property(property="A3_NO", type="string", format="string", example="17/51"),
 *              @OA\Property(property="A3_MOI", type="string", format="string"),
 *              @OA\Property(property="A3_VILLAGE", type="string", format="string"),
 *              @OA\Property(property="A3_BUILDING", type="string", format="string"),
 *              @OA\Property(property="A3_FLOOR", type="string", format="string"),
 *              @OA\Property(property="A3_ROOM_NO", type="string", format="string"),
 *              @OA\Property(property="A3_SOI", type="string", format="string"),
 *              @OA\Property(property="A3_ROAD", type="string", format="string"),
 *              @OA\Property(property="A3_PROVINCE", type="string", format="string", example="10"),
 *              @OA\Property(property="A3_DISTRICT", type="string", format="string", example="1003"),
 *              @OA\Property(property="A3_SUBDISTRICT", type="string", format="string", example="100302"),
 *              @OA\Property(property="A3_POSTALCODE", type="string", format="string", example="10530"),
 *              @OA\Property(property="A3_OWNER_TYPE", type="string", format="string", example="8"),
 *              @OA\Property(property="A3_LIVEING_TIME", type="string", format="string"),
 *              @OA\Property(property="A3_PHONE", type="string", format="string")
 * 
 *          ),
 *      ),
 *   ),
 * @OA\Response(
 *    response=422,
 *    description="Wrong credentials response",
 *    @OA\JsonContent(
 *       @OA\Property(property="Code", type="string", example="9999"),
 *       @OA\Property(property="status", type="string", example="Sucsess"),
 *       @OA\Property(property="data", type="string", example="[...]"),
 *        )
 *     )
 * ),
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * @OA\Post(
 * path="/api/SKUCheckDownGua",
 * summary="ตรวจสอบจำนวนเงินดาวน์ขั้นต่ำ , ผู้ค้ำประกัน",
 * description="new custome",
 * operationId="SKUCheckDownGua",
 * tags={"API Check"},
 *   @OA\RequestBody(
 *      required=true,
 *      description="Product information",
 *      @OA\JsonContent(
 *         required={"PRODUCT_SERIES", "FACULTY_ID", "UNIVERSITY_ID"},
 *         @OA\Property(property="PRODUCT_SERIES", type="string", format="string", example="190199657816"),
 *         @OA\Property(property="FACULTY_ID", type="string", format="string", example="807"),
 *         @OA\Property(property="UNIVERSITY_ID", type="string", format="string", example="13"),
 *         @OA\Property(property="ACS_ID", type="string", format="string", example="6"),
 *         @OA\Property(property="INSURE_ID", type="string", format="string", example="1"),
 *      ),
 *   ),
 * @OA\Response(
 *    response=422,
 *    description="Wrong credentials response",
 *    @OA\JsonContent(
 *       @OA\Property(property="Code", type="string", example="9999"),
 *       @OA\Property(property="status", type="string", example="Sucsess"),
 *       @OA\Property(property="data", type="string", example="[...]"),
 *        )
 *     )
 * ),
 * 
 * 
 * 
 * 
 *
 * @OA\Post(
 * path="/api/SKU_ASSETS_INSURANCE",
 * summary="รายการประกันและอุปกรณ์เสริมของสินค้า",
 * description="รายการประกันและอุปกรณ์เสริมของสินค้า",
 * operationId="ASSETS_INSURANCE",
 * tags={"API Check"},
 *   @OA\RequestBody(
 *      required=true,
 *      description="Product information",
 *      @OA\JsonContent(
 *         required={"PRODUCT_SERIES"},
 *         @OA\Property(property="PRODUCT_SERIES", type="string", format="string", example="194252038338"),
 *      ),
 *   ),
 * @OA\Response(
 *    response=422,
 *    description="Wrong credentials response",
 *    @OA\JsonContent(
 *       @OA\Property(property="Code", type="string", example="9999"),
 *       @OA\Property(property="status", type="string", example="Sucsess"),
 *       @OA\Property(property="data", type="string", example="[...]"),
 *        )
 *     )
 * ),
 *  
 * 
 * 
 * @OA\Post(
 * path="/api/Check_Tenor",
 * summary="จำนวนงวด",
 * description="จำนวนงวดที่สามารถเลือกได้",
 * operationId="Check_Tenor",
 * tags={"API Check"},
 *   @OA\RequestBody(
 *      required=true,
 *      description="Price information",
 *      @OA\JsonContent(
 *         required={"PRODUCT_SERIES"},
 *         @OA\Property(property="PROD_SUM_PRICE", type="string", format="string", example="29900"),
 *      ),
 *   ),
 * @OA\Response(
 *    response=422,
 *    description="Wrong credentials response",
 *    @OA\JsonContent(
 *       @OA\Property(property="Code", type="string", example="9999"),
 *       @OA\Property(property="status", type="string", example="Sucsess"),
 *       @OA\Property(property="data", type="string", example="[...]"),
 *        )
 *     )
 * ),
 * 
 * 
 */

class Controller extends BaseController
{
    // php artisan l5-swagger:generate
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    // $query = str_replace(array('?'), array('\'%s\''), $MT->toSql());
    // $query = vsprintf($query, $MT->getBindings());
    // dump($query);

    public function Void(){

    }

}



// Backup
/*

 * 
 * @OA\Post(
 * path="/api/SKU_ASSETS",
 * summary="รายการอุปกรณ์เสริม",
 * description="รายการรอุปกรณ์เสริมของสินค้า",
 * operationId="sku_Accessories",
 * tags={"API Check"},
 *   @OA\RequestBody(
 *      required=true,
 *      description="Product information",
 *      @OA\JsonContent(
 *         required={"PRODUCT_SERIES"},
 *         @OA\Property(property="PRODUCT_SERIES", type="string", format="string", example="190199807716"),
 *      ),
 *   ),
 * @OA\Response(
 *    response=422,
 *    description="Wrong credentials response",
 *    @OA\JsonContent(
 *       @OA\Property(property="Code", type="string", example="9999"),
 *       @OA\Property(property="status", type="string", example="ฆฤSucsess"),
 *       @OA\Property(property="data", type="string", example="[...]"),
 *        )
 *     )
 * ),
 * 
 * 
 * @OA\Post(
 * path="/api/SKU_Warrantee",
 * summary="ประกันเสริม",
 * description="ประกันเสริมของสินค้า",
 * operationId="Warrantee",
 * tags={"API Check"},
 *   @OA\RequestBody(
 *      required=true,
 *      description="Product information",
 *      @OA\JsonContent(
 *         required={"PRODUCT_SERIES"},
 *         @OA\Property(property="PRODUCT_SERIES", type="string", format="string", example="194252038338"),
 *      ),
 *   ),
 * @OA\Response(
 *    response=422,
 *    description="Wrong credentials response",
 *    @OA\JsonContent(
 *       @OA\Property(property="Code", type="string", example="9999"),
 *       @OA\Property(property="status", type="string", example="Sucsess"),
 *       @OA\Property(property="data", type="string", example="[...]"),
 *        )
 *     )
 * ),

*/



/*

 * 
 * 
 * @OA\Post(
 * path="/api/new_customer",
 * summary="Insert new customer",
 * description="new custome <br> ข้อมูลสินค้า",
 * operationId="new_customer",
 * tags={"API QUOTATION"},
 * security={{ "Api-Token": {} }},
 *   @OA\RequestBody(
 *      required=true,
 *      description="Customer information",
 *      @OA\JsonContent(
 *         required={"BRANCH_TYPE", "BRANCH_ID", "TAX_ID", "CUSTOMER_NAME", "OCCUPATION_ID", "UNIVERSITY_ID", "FACULTY_ID", "PRODUCT_SERIES", "PROD_SUM_PRICE", "DOWN_SUM_AMT"},
 *         @OA\Property(property="BRANCH_TYPE", type="string", format="string", example="1"),
 *         @OA\Property(property="BRANCH_ID", type="string", format="string", example="31"),
 *         @OA\Property(property="TAX_ID", type="string", format="string", example="4856465441239"),
 *         @OA\Property(property="CUSTOMER_NAME", type="string", format="string", example="TEST API"),
 *         @OA\Property(property="OCCUPATION_ID", type="string", format="string", example="2"),
 *         @OA\Property(property="UNIVERSITY_ID", type="string", format="string", example="13"),
 *         @OA\Property(property="FACULTY_ID", type="string", format="string", example="807"),
 *         @OA\Property(property="PRODUCT_SERIES", type="string", format="string", example="194252038338"),
 *         @OA\Property(property="DOWN_SUM_AMT", type="string", format="string", example="3540"),
 *         @OA\Property(property="INSTALL_NUM", type="string", format="string", example="24"),
 *         @OA\Property(property="ACS_ID", type="string", format="string", example="484"),
 *         @OA\Property(property="ACS_DES", type="string", format="string", example="Apple Pencil 2"),
 *         @OA\Property(property="ACS_SUM", type="string", format="string", example="4190"),
 *         @OA\Property(property="INSURE_ID", type="string", format="string", example="42"),
 *         @OA\Property(property="INSURE_DES", type="string", format="string", example="AppleCare+ for iPhone 13"),
 *         @OA\Property(property="INSURE_SUM", type="string", format="string", example="5090"),
 *      ),
 *   ),
 * @OA\Response(
 *    response=422,
 *    description="Wrong credentials response",
 *    @OA\JsonContent(
 *       @OA\Property(property="Code", type="string", example="9999"),
 *       @OA\Property(property="status", type="string", example="Sucsess"),
 *       @OA\Property(property="data", type="string", example="[...]"),
 *        )
 *     )
 * ),
 * 
 * 
 * @OA\Post(
 * path="/api/new_prospect_cus",
 * summary="Insert new customer",
 * description="new prospect <br> ข้อมูลลูกค้า",
 * operationId="new_prospect",
 * tags={"API QUOTATION"},
 * security={{ "Api-Token": {} }},
 *   @OA\RequestBody(
 *      required=true,
 *      description="Prospect customer information",
 *      @OA\MediaType(
 *          mediaType="multipart/form-data",
 *          @OA\Schema(
 *              required={"PST_CUST_ID", "QUOTATION_ID", "TAX_ID", "PREFIX", "FIRST_NAME", "LAST_NAME", "BIRTHDAY", "SEX", "MARITAL_STATUS", "PHONE", "EMAIL", 
 *                        "OCCUPATION_CODE", "MAIN_INCOME", "UNIVERSITY_PROVINCE", "UNIVERSITY_NAME", "FACULTY_NAME", "LEVEL_TYPE", "U_LEVEL", "LOAN_KYS", "REF_TAX_ID", 
 *                        "REF_TITLE", "REF_FIRSTNAME", "REF_LASTNAME", "RELATION_REFERENCE", "REF_OCCUPATION", "REF_BIRTHDAY", "REF_PHONE", "IDCARD_FILE", "STUDENTCARD_FILE", 
 *                        "FACE_FILE", "STUDENT_ID"},
 *              @OA\Property(property="PST_CUST_ID", type="string", format="string", example="768"),
 *              @OA\Property(property="QUOTATION_ID", type="string", format="string", example="814"),
 *              @OA\Property(property="PREFIX", type="string", format="string", example="2"),
 *              @OA\Property(property="FIRST_NAME", type="string", format="string", example="ทดสอบ"),
 *              @OA\Property(property="LAST_NAME", type="string", format="string", example="เอพีไอ"),
 *              @OA\Property(property="TAX_ID", type="int", format="int", example="4856465441255"),
 *              @OA\Property(property="STUDENT_ID", type="int", format="int", example="1122334455"),
 *              @OA\Property(property="BIRTHDAY", type="string", format="date", example="2544-10-18"),
 *              @OA\Property(property="SEX", type="string", format="string", example="1"),
 *              @OA\Property(property="MARITAL_STATUS", type="string", format="string", example="1"),
 *              @OA\Property(property="PHONE", type="string", format="string", example="0812345678"),
 *              @OA\Property(property="EMAIL", type="string", format="email", example="test@hotmail.com"),
 *              @OA\Property(property="FACEBOOK", type="string", format="string"),
 *              @OA\Property(property="LINEID", type="string", format="string"),
 *              @OA\Property(property="OCCUPATION_CODE", type="int", format="int", example="2"),
 *              @OA\Property(property="MAIN_INCOME", type="int", format="int", example="5000"),
 *              @OA\Property(property="UNIVERSITY_PROVINCE", type="int", format="int", example="10"),
 *              @OA\Property(property="UNIVERSITY_DISTRICT", type="int", format="int", example="1007"),
 *              @OA\Property(property="UNIVERSITY_ID", type="int", format="int", example="2"),
 *              @OA\Property(property="FACULTY_ID", type="int", format="int", example="47"),
 *              @OA\Property(property="FACULTY_OTHER", type="string", format="string"),
 *              @OA\Property(property="LEVEL_TYPE", type="string", format="string", example="ปริญญาตรี"),
 *              @OA\Property(property="U_LEVEL", type="int", format="int", example="2"),
 *              @OA\Property(property="LOAN_KYS", type="string", format="string", example="ํYES"),
 *              @OA\Property(property="REF_TAX_ID", type="int", format="int", example="1122233339310"),
 *              @OA\Property(property="REF_TITLE", type="string", format="string", example="1"),
 *              @OA\Property(property="REF_TITLE_OTHER", type="string", format="string"),
 *              @OA\Property(property="REF_FIRSTNAME", type="string", format="string", example="นายทดสอบ"),
 *              @OA\Property(property="REF_LASTNAME", type="string", format="string", example="สกุลทดสอบ"),
 *              @OA\Property(property="RELATION_REFERENCE", type="int", format="int", example="1"),
 *              @OA\Property(property="REF_OCCUPATION", type="int", format="int", example="49"),
 *              @OA\Property(property="REF_BIRTHDAY", type="string", format="date", example="2544-10-18"),
 *              @OA\Property(property="REF_PHONE", type="string", format="string", example="0876543211"),
 *              @OA\Property(property="IDCARD_FILE", type="file", format="binary" ),
 *              @OA\Property(property="STUDENTCARD_FILE", type="file", format="binary" ),
 *              @OA\Property(property="FACE_FILE", type="file", format="binary" ),
 *              @OA\Property(property="URLMAP", type="string", format="string", example="https://www.google.co.th/maps"),
 *              @OA\Property(property="EMAILGuarantor", type="string", format="string"),
 *          ),
 *      ),
 *   ),
 * @OA\Response(
 *    response=422,
 *    description="Wrong credentials response",
 *    @OA\JsonContent(
 *       @OA\Property(property="Code", type="string", example="9999"),
 *       @OA\Property(property="status", type="string", example="Sucsess"),
 *       @OA\Property(property="data", type="string", example="[...]"),
 *        )
 *     )
 * ),
 * 
 * 
 * @OA\Post(
 * path="/api/new_address_prospect",
 * summary="Insert new customer",
 * description="new address prospect <br> ข้อมูลที่อยู่ลูกค้า <br>A1.ทะเบียนบ้าน <br> A2.ที่อยู่ปัจจุบัน <br> A3.ที่อยู่จัดส่งสินค้า หรือ จัดส่งเอกสาร",
 * operationId="new_address_prospect",
 * tags={"API QUOTATION"},
 * security={{ "Api-Token": {} }},
 *   @OA\RequestBody(
 *      required=true,
 *      description="Address information",
 *      @OA\JsonContent(
 *         required={"QUOTATION_ID", "PST_CUST_ID", "A1_NO", "A1_PROVINCE", "A1_DISTRICT", "A1_SUBDISTRICT", "A1_POSTALCODE", "A1_OWNER_TYPE", "A2_NO", "A2_PROVINCE", "A2_DISTRICT", "A2_SUBDISTRICT", "A2_POSTALCODE", "A2_OWNER_TYPE", "A3_NO", "A3_PROVINCE", "A3_DISTRICT", "A3_SUBDISTRICT", "A3_POSTALCODE", "A3_OWNER_TYPE" },
 *         @OA\Property(property="QUOTATION_ID", type="string", format="string", example="592"),
 *         @OA\Property(property="PST_CUST_ID", type="string", format="string", example="629"),
 *         @OA\Property(property="A1_NO", type="string", format="string", example="17/51", description="บ้านเลขที่"),
 *         @OA\Property(property="A1_MOI", type="string", format="string", example=null, description="หมู่ที่"),
 *         @OA\Property(property="A1_VILLAGE", type="string", format="string", example=null, description="หมู่บ้าน/โครงการ"),
 *         @OA\Property(property="A1_BUILDING", type="string", format="string", example=null, description="อาคาร"),
 *         @OA\Property(property="A1_FLOOR", type="string", format="string", example=null, description="ชั่น"),
 *         @OA\Property(property="A1_ROOM_NO", type="string", format="string", example=null, description="เลขที่ห้อง"),
 *         @OA\Property(property="A1_SOI", type="string", format="string", example=null, description="ซอย"),
 *         @OA\Property(property="A1_ROAD", type="string", format="string", example=null, description="ถนน"),
 *         @OA\Property(property="A1_PROVINCE", type="string", format="string", example="10", description="จังหวัด"),
 *         @OA\Property(property="A1_DISTRICT", type="string", format="string", example="1003", description="อำเภอ"),
 *         @OA\Property(property="A1_SUBDISTRICT", type="string", format="string", example="100302", description="ตำบล"),
 *         @OA\Property(property="A1_POSTALCODE", type="string", format="string", example="10530", description="รหัสไปรษณี"),
 *         @OA\Property(property="A1_OWNER_TYPE", type="string", format="string", example="8", description="สถานะการพักอาศัย"),
 *         @OA\Property(property="A1_LIVEING_TIME", type="string", format="string", example=null, description="ระยะเวลาที่อาศัยอยู่"),
 *         @OA\Property(property="A1_PHONE", type="string", format="string", example=null, description="เบอร์โทรศัทพ์"),
 *         @OA\Property(property="A2_NO", type="string", format="string", example="17/51"),
 *         @OA\Property(property="A2_MOI", type="string", format="string", example=null),
 *         @OA\Property(property="A2_VILLAGE", type="string", format="string", example=null),
 *         @OA\Property(property="A2_BUILDING", type="string", format="string", example=null),
 *         @OA\Property(property="A2_FLOOR", type="string", format="string", example=null),
 *         @OA\Property(property="A2_ROOM_NO", type="string", format="string", example=null),
 *         @OA\Property(property="A2_SOI", type="string", format="string", example=null),
 *         @OA\Property(property="A2_ROAD", type="string", format="string", example=null),
 *         @OA\Property(property="A2_PROVINCE", type="string", format="string", example="10"),
 *         @OA\Property(property="A2_DISTRICT", type="string", format="string", example="1003"),
 *         @OA\Property(property="A2_SUBDISTRICT", type="string", format="string", example="100302"),
 *         @OA\Property(property="A2_POSTALCODE", type="string", format="string", example="10530"),
 *         @OA\Property(property="A2_OWNER_TYPE", type="string", format="string", example="8"),
 *         @OA\Property(property="A2_LIVEING_TIME", type="string", format="string", example=null),
 *         @OA\Property(property="A2_PHONE", type="string", format="string", example=null),
 *         @OA\Property(property="A3_NO", type="string", format="string", example="17/51"),
 *         @OA\Property(property="A3_MOI", type="string", format="string", example=null),
 *         @OA\Property(property="A3_VILLAGE", type="string", format="string", example=null),
 *         @OA\Property(property="A3_BUILDING", type="string", format="string", example=null),
 *         @OA\Property(property="A3_FLOOR", type="string", format="string", example=null),
 *         @OA\Property(property="A3_ROOM_NO", type="string", format="string", example=null),
 *         @OA\Property(property="A3_SOI", type="string", format="string", example=null),
 *         @OA\Property(property="A3_ROAD", type="string", format="string", example=null),
 *         @OA\Property(property="A3_PROVINCE", type="string", format="string", example="10"),
 *         @OA\Property(property="A3_DISTRICT", type="string", format="string", example="1003"),
 *         @OA\Property(property="A3_SUBDISTRICT", type="string", format="string", example="100302"),
 *         @OA\Property(property="A3_POSTALCODE", type="string", format="string", example="10530"),
 *         @OA\Property(property="A3_OWNER_TYPE", type="string", format="string", example="8"),
 *         @OA\Property(property="A3_LIVEING_TIME", type="string", format="string", example=null),
 *         @OA\Property(property="A3_PHONE", type="string", format="string", example=null),
 *      ),
 *   ),
 * @OA\Response(
 *    response=422,
 *    description="Wrong credentials response",
 *    @OA\JsonContent(
 *       @OA\Property(property="Code", type="string", example="9999"),
 *       @OA\Property(property="status", type="string", example="Sucsess"),
 *       @OA\Property(property="data", type="string", example="[...]"),
 *        )
 *     )
 * ),

*/




/* API Product

* @OA\Get(
 * path="/api/master_category",
 * summary="Master Data หมวดสินค้า",
 * description="หมวดสินค้า",
 * operationId="master_category",
 * tags={"API Product"},
 * @OA\Response(
 *    response=422,
 *    description="Wrong credentials response",
 *    @OA\JsonContent(
 *       @OA\Property(property="Code", type="string", example="9999"),
 *       @OA\Property(property="status", type="string", example="Sucsess"),
 *       @OA\Property(property="data", type="string", example="[...]"),
 *        )
 *     )
 * ),
 * 
 * @OA\Get(
 * path="/api/master_brand",
 * summary="Master Data ยี่ห้อสินค้า",
 * description="ยี่ห้อสินค้า",
 * operationId="master_brand",
 * tags={"API Product"},
 * @OA\Response(
 *    response=422,
 *    description="Wrong credentials response",
 *    @OA\JsonContent(
 *       @OA\Property(property="Code", type="string", example="9999"),
 *       @OA\Property(property="status", type="string", example="Sucsess"),
 *       @OA\Property(property="data", type="string", example="[...]"),
 *        )
 *     )
 * ),
 * 
 * @OA\Get(
 * path="/api/master_series/{BRAND_ID}",
 * summary="Master Data รุ่นสินค้า",
 * description="รุ่นสินค้า",
 * operationId="master_series",
 * tags={"API Product"},
 * @OA\Parameter(
 *     name="BRAND_ID",
 *     in="path",
 *     description="รหัสยี่ห้อ เช่น 9",
 *     required=false,
 * ),
 * @OA\Response(
 *    response=422,
 *    description="Wrong credentials response",
 *    @OA\JsonContent(
 *       @OA\Property(property="Code", type="string", example="9999"),
 *       @OA\Property(property="status", type="string", example="Sucsess"),
 *       @OA\Property(property="data", type="string", example="[...]"),
 *        )
 *     )
 * ),
 * 
 * @OA\Get(
 * path="/api/master_sub_series/{SERIES_ID}",
 * summary="Master Data ความจุ",
 * description="ความจุ",
 * operationId="master_sub_series",
 * tags={"API Product"},
 * @OA\Parameter(
 *     name="SERIES_ID",
 *     in="path",
 *     description="รหัสรุ่นสินค้า เช่น 59",
 *     required=false,
 * ),
 * @OA\Response(
 *    response=422,
 *    description="Wrong credentials response",
 *    @OA\JsonContent(
 *       @OA\Property(property="Code", type="string", example="9999"),
 *       @OA\Property(property="status", type="string", example="Sucsess"),
 *       @OA\Property(property="data", type="string", example="[...]"),
 *        )
 *     )
 * ),
 * 
 * @OA\Get(
 * path="/api/master_color/{SERIES_ID}",
 * summary="Master Data สี",
 * description="สี",
 * operationId="master_color",
 * tags={"API Product"},
 * @OA\Parameter(
 *     name="SERIES_ID",
 *     in="path",
 *     description="รหัสรุ่นสินค้า เช่น 59",
 *     required=false,
 * ),
 * @OA\Response(
 *    response=422,
 *    description="Wrong credentials response",
 *    @OA\JsonContent(
 *       @OA\Property(property="Code", type="string", example="9999"),
 *       @OA\Property(property="status", type="string", example="Sucsess"),
 *       @OA\Property(property="data", type="string", example="[...]"),
 *        )
 *     )
 * ),
 * 
 * @OA\Get(
 * path="/api/master_assets_information",
 * summary="Master Data อุปกรณ์เสริม",
 * description="อุปกรณ์เสริม",
 * operationId="master_assets_information",
 * tags={"API Product"},
 * @OA\Response(
 *    response=422,
 *    description="Wrong credentials response",
 *    @OA\JsonContent(
 *       @OA\Property(property="Code", type="string", example="9999"),
 *       @OA\Property(property="status", type="string", example="Sucsess"),
 *       @OA\Property(property="data", type="string", example="[...]"),
 *        )
 *     )
 * ),
 * 
 * @OA\Get(
 * path="/api/master_insure/{SERIES_ID}",
 * summary="Master Data บริการคุ้มครองเสริม",
 * description="บริการคุ้มครองเสริม",
 * operationId="master_insure",
 * tags={"API Product"},
 * @OA\Parameter(
 *     name="SERIES_ID",
 *     in="path",
 *     description="รหัสรุ่นสินค้า เช่น 59",
 *     required=false,
 * ),
 * @OA\Response(
 *    response=422,
 *    description="Wrong credentials response",
 *    @OA\JsonContent(
 *       @OA\Property(property="Code", type="string", example="9999"),
 *       @OA\Property(property="status", type="string", example="Sucsess"),
 *       @OA\Property(property="data", type="string", example="[...]"),
 *        )
 *     )
 * ),

*/