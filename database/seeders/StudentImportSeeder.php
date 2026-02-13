<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Nurse;
use App\Models\Unit;
use App\Models\Station;
use App\Models\Room;
use App\Models\Bed;
use App\Models\NurseType;

class StudentImportSeeder extends Seeder
{
    public function run(): void
    {
        $password = Hash::make('1234567');
        $testPassword = Hash::make('password');

        // ==================================================
        // 1. DEFINE STATION TYPES (Per Unit)
        // ==================================================
        $stationTypes = [
            ['code' => 'ER', 'name' => 'Emergency Room', 'type' => 'ER', 'is_clinical' => true, 'cap' => 5],
            ['code' => 'ICU', 'name' => 'Intensive Care', 'type' => 'ICU', 'is_clinical' => true, 'cap' => 4],
            ['code' => 'MS', 'name' => 'Medical-Surgical Ward', 'type' => 'Ward', 'is_clinical' => true, 'cap' => 6],
            ['code' => 'OB', 'name' => 'OB-GYN Ward', 'type' => 'Ward', 'is_clinical' => true, 'cap' => 4],
            ['code' => 'PVT', 'name' => 'Private Wing', 'type' => 'Private', 'is_clinical' => true, 'cap' => 3],
            ['code' => 'OPD', 'name' => 'Outpatient Dept', 'type' => null, 'is_clinical' => true, 'cap' => 0],
            ['code' => 'ADM', 'name' => 'Admissions Office', 'type' => null, 'is_clinical' => false, 'cap' => 0],
        ];

        // Clinical station codes (for distributing students)
        $clinicalStationCodes = ['ER', 'ICU', 'MS', 'OB', 'PVT', 'OPD'];

        // ==================================================
        // 2. STUDENT DATA GROUPED BY SECTION (subjid)
        // ==================================================
        $studentsBySection = [
            2638 => [
                ['CON240679', 'ANTE, GLAINRY ROSE BASAN'],
                ['CON240768', 'CAVANZA, ZAIRELLE AGRAO'],
                ['CON240694', 'AROJADO, HANNAH MICAELAH ROSE DELEN'],
                ['CON240666', 'PEREZ, CHERRY MAE CONSIGNA'],
                ['CON240862', 'BORILLO, JEAN LOREEN MONTERO'],
                ['CON240700', 'EUGENIO, AZIL DON DOTIG'],
                ['CON240900', 'MORTE, KAREN NICOLE ALFONSO'],
                ['CON240697', 'CUETO, RONAIZA CLAIRE CUASAY'],
                ['CON240767', 'EVANGELISTA, JULLIENE MARIE CULIAT'],
                ['CON240696', 'MANALO, EIRROLE CLARENZE ABACAN'],
                ['CON240924', 'MANGUBAT, FAYE SAPUNGAN'],
                ['CON240684', 'SERRANO, MARY JOY ELLISE BARTOLOME'],
                ['CON240770', 'LATOZA, PRINCESS NIÑA SUAREZ'],
                ['CON240776', 'ALEA, JAYNE ANN DAGLI'],
                ['CON240685', 'TIMBAS, JOHANNAH MARIE NINGUA'],
                ['CON240701', 'BACAMANTE, JEWEL MAE BUENDIA'],
                ['CON240728', 'ENCARNACION, ANNE PAULINE CACAO'],
                ['CON240993', 'SARCINAS, ROMNICK REYES'],
                ['CON240973', 'SALIGAO, JASMINE CEPILLO'],
                ['CON240664', 'BARTE, KATHLENE LOUISE MENDOZA'],
                ['CON240698', 'AÑONUEVO, PRINCESS VANNA CAIAH MACATANGAY'],
                ['CON240987', 'RONQUILLO, JANBERT YUZEIPH DAVID'],
                ['CON240703', 'DIMAANO, JANE YASMEN ALMAREZ'],
                ['CON240690', 'MAGSINO, ANDRIELLE BANAAG'],
                ['CON240711', 'AGULTO, DIANNA FAYE GARCIA'],
                ['CON240687', 'TENORIO, MAICA MENDOZA'],
                ['CON241025', 'GONZALES, ERICH DIMAANDAL'],
                ['CON241011', 'SAMONTE, NATHAN GERMAIN ALDAWISA'],
                ['CON240894', 'JORDAN, RENELYN JOY VILLAREZ'],
                ['CON240880', 'BOOL, ALEIJA CLOE DELA VIRGEN'],
                ['CON240901', 'JINTALAN, ZEIRE DHEL RAMIREZ'],
                ['CON240713', 'MORALES, ELOISA MARIE ARANDIA'],
                ['CON220254', 'CORTEZ, KRISTELLE JOY UNTALAN'],
            ],
            2706 => [
                ['CON240765', 'ACOSTA, JILLIAN DUENAS'],
                ['CON240869', 'AGUILA, JONALOU AGUILON'],
                ['CON240704', 'PARANTES, SAMANODEN MACALAWAN'],
                ['CON240683', 'MANANSALA, HELENA REIGN UNTALAN'],
                ['CON240715', 'ROBLES, RAJIENDER ADAJAR'],
                ['CON240712', 'AÑONUEVO, LANCE ANDREY CANTOS'],
                ['CON240848', 'DIMAANO, ANTONIETTE FERRER'],
                ['CON240934', 'RONQUILLO, ELLAINE AGUSTIN'],
                ['2016013952', 'ARNAIZ, JULIET IMAN'],
                ['CON240753', 'GAMBOA, ALYZZA JEAN MORALES'],
                ['CON240740', 'PINEDA, LEHYAN ANGELA MARQUEZ'],
                ['CON240884', 'PEDEMONTE, MIYUMI ACLAN'],
                ['CON240803', 'DELIZO, ARYANA KHIM AGUSTIN'],
                ['CON240719', 'ORTEGA, SHERLYN MAE MACATANGAY'],
                ['CON240680', 'VERGARA, MARY JAYZEL MANALO'],
                ['CON240668', 'SEVILLA, KAYCEE ANNE GUERIÑA'],
                ['CON240689', 'FAJILAN, LANCE RUSSEL FALLARCUNA'],
                ['CON240724', 'GUNO, LHIANA NICOLE DE TORRES'],
                ['CON240727', 'ILAGAN, SAMANTHA JEWEL LIWAG'],
                ['CON240737', 'OFQUERIA, ALLAINE LASTIMOSA'],
                ['CON240972', 'RAMOS, KRISHA NIÑA VERSOZA'],
                ['CON240860', 'ALEGRE, MARYLOUDS PANGANIBAN'],
                ['CON240758', 'AN, JHOVENICA MAE ALIWALAS'],
                ['CON240669', 'DINGLASAN, KYLA PEREZ'],
                ['CON240813', 'ROSALES, LIMUEL MACATANGAY'],
                ['CON240720', 'CABRERA, ALESSANDRA ZHYRA NAPA'],
                ['CON240673', 'DE AUSTRIA, SHEENA JERANE CASTILLO'],
                ['CON240819', 'MEDRANO, MA. JOENETTE ALCOBA'],
                ['CON240849', 'ARAGO, PIXELA WIRENA PLATA'],
                ['CON240861', 'SALAZAR, PRINCESS KRIZZA ALANGILAN'],
                ['CON240857', 'SULIT, ALEISSA JANNAH DINOPOL'],
                ['CON240732', 'EGUIA, CHRISTINE BUSILIG'],
                ['CON240872', 'MACATANGAY, KIM LUIS ARAGO'],
            ],
            2707 => [
                ['CON240842', 'ORTEGA, JHELLAN MAE DEL PRADO'],
                ['CON240681', 'DELA ROSA, CLARENZE JOHN CIOCSON'],
                ['CON240756', 'OGA-A, TRISHA MHAE DE LA CRUZ'],
                ['CON240949', 'MABINI, ALEA SHANE DE TORRES'],
                ['CON240678', 'RIVERA, JAMMELA MEI LOPEZ'],
                ['CON240835', 'PEREZ, SHEEHAN IRISH LUMANGLAS'],
                ['CON240925', 'ALMARIO, HANNAH ASHLEY CATIBOG'],
                ['CON240722', 'MANALO, J-CEE ANN MAE PEREZ'],
                ['CON240766', 'BUENO, JOHN CARLO CARINGAL'],
                ['CON240976', 'MALABANAN, JESSAN HAYLO'],
                ['CON240953', 'MALLILLIN, CHANELLE HERNANDEZ'],
                ['CON230657', 'PERSINCULA, CATALEEN JOSSH LONTOK'],
                ['CON240675', 'MERCADO, ANGELENE CUNAG'],
                ['CON240742', 'MEJICO, CHARMAINE BARROGA'],
                ['CON240699', 'CUETO, AIKEN PIERRE MARQUEZ'],
                ['CON240667', 'BELBES, DENZ ANDREI CONTI'],
                ['CON240692', 'DE LOS REYES, QUENGEL MHILDAN RICO'],
                ['CON240824', 'AREVALO, ROBBIE MILES BALANI'],
                ['CON240823', 'MANALO, MARY CRIS CANTA'],
                ['CON240844', 'MANONGSONG, JAIMEE ANDREA CAPUNO'],
                ['CON240762', 'ANOG, JOANAH MARIE IMPERIAL'],
                ['CON240833', 'MARQUEZ, MATTEUS CHRISTOPHER MARASIGAN'],
                ['CON240797', 'AGUILA, ANGELA MAE AGNOTE'],
                ['CON240923', 'CABRAL, QUEEN ALTHEA MARI ALCAYDE'],
                ['CON240802', 'MABUNGA, PRINCESS AHRWEN CASTILLO'],
                ['CON240782', 'FIDEL, REALENE ANDAL'],
                ['CON240838', 'MITRA, LORAINE SHANE SANCHEZ'],
                ['CON240885', 'REYES, ANGELA SOLIMAN'],
                ['CON240843', 'RONQUILLO, JASMINE ANDREA LALONGISIP'],
                ['CON241031', 'ADUNAY, JEFFRY HUMILDE'],
                ['CON240726', 'SALINAS, HERSCHEL SALANGUIT'],
                ['CON240882', 'SERRANO, XYRINN FAITH MARCELO'],
                ['CON240929', 'DOROÑO, BIANCA ADRIENNE BALMES'],
                ['CON240750', 'EGUIA, WINCELL JANE OMNES'],
                ['CON240858', 'BERANIA, ARIO NAZ TONDO'],
                ['CON240784', 'ABAD, FIONA MARIE ANDE'],
                ['CON240980', 'COLLADO, RYAN JOSHUA MACALINTAL'],
            ],
            2708 => [
                ['CON240769', 'CONTRERAS, BERNADETH QUILICOL'],
                ['CON240911', 'FARAON, JENNYSIS ALLAIN CHAVEZ'],
                ['CON240780', 'LONTOC, JASPER KIRBY HERNANDEZ'],
                ['CON240806', 'EYAO, JAEZELLE MCREIGN MARASIGAN'],
                ['CON240985', 'ABELLO, FRITZIE GALE DE TORRES'],
                ['CON240808', 'IBON, EUNICE ABEGAIL ALMAREZ'],
                ['CON240902', 'EBORA, BRENDA ELAINE PAGLINAWAN'],
                ['CON240836', 'GERON, LEANA MARIE CACHERO'],
                ['CON240779', 'ANDAL, PRINCES ERICH ENRIQUEZ'],
                ['CON240930', 'BAYON, IVY YASIS'],
                ['CON240725', 'CANTOS, ERIKA JULIANA DE GUZMAN'],
                ['CON240702', 'DELGADO, CAMILLE MAALIHAN'],
                ['CON240801', 'FALTADO, CHRISTINE MAE BERMON'],
                ['CON240839', 'GIMAN, KHRISTHEL ANN NECOR'],
                ['CON240886', 'HERNANDEZ, VANNAH CLARISSE PENTINIO'],
                ['CON240671', 'MAGAT, JANNISE SAMANTHA BANTA'],
                ['CON240927', 'MERCADO, JAN FRENCIE TAPAY'],
                ['CON240850', 'ABE, KIMBERLY MAE CABANATAN'],
                ['CON240672', 'MIRANO, DANICA NACARIO'],
                ['CON240731', 'AGUILA, JOSHUA EMMANUEL ARSAGA'],
                ['CON240964', 'CUETO, ANGELINE CASTILLO'],
                ['CON240963', 'BATICOS, HYRUM LEE CARIÑO'],
                ['CON240896', 'BAES, MA. LUZ COSTA'],
                ['CON240945', 'MARASIGAN, MICAH DE LEON'],
                ['CON240851', 'ABRIGONDA, PRINCESS AEISHA AGUIRRE'],
                ['CON240991', 'CUNANAN, CHERLYN FAITH'],
                ['CON240818', 'ABANES, THAZINARIE DINGLASAN'],
                ['CON240754', 'ANGULO, RYZEL CASTILLO'],
                ['CON240709', 'BRAZA, MARY MAE ANGEL BAUTISTA'],
                ['CON240852', 'GUMAPAC, ALIYAH CLAVERIA'],
                ['CON241022', 'PANGANIBAN, BRIANE JUSTIN CARA'],
                ['CON240674', 'LIPA, EL RUEL MENDEZ'],
                ['CON240795', 'MIRAPLES, ALLYSSA SHEINELLE PASCUA'],
                ['CON240734', 'MENDOZA, CARL JUSTINE LACABA'],
                ['CON240994', 'ILAGAN, KENT OLIVER PASUMBAL'],
                ['CON240997', 'JASA, TRESIAMAE ESPARRAGO'],
                ['CON240998', 'COSTES, HERMIONE SELENE ATIENZA'],
            ],
            2709 => [
                ['CON240774', 'PAL, CARL VINCENT ACUÑA'],
                ['CON240816', 'COMEZ, PRECIOUS JEWELL SANVICTORES'],
                ['CON240840', 'TAÑANG, KEIZHA MACALALAD'],
                ['CON240820', 'PADOLINA, JOHN CARLO MASILANG'],
                ['CON240682', 'ANDAL, JOHN MARCO RAMOS'],
                ['CON240990', 'FORMASIDORO, ALYSSA DELICA'],
                ['CON240763', 'MANALO, KRISTINE JANE LINCALLO'],
                ['CON240789', 'ARGENTE, FAYE YVETTE ATIENZA'],
                ['CON240960', 'CUETO, JOSHUA DATINGALING'],
                ['CON240984', 'APRITADO, JUSTIN GELERA'],
                ['CON240792', 'BARTOLAY, WILLIARD GUTIERREZ'],
                ['CON240781', 'BOBADILLA, CHEREY ANN NICOLE JUBAHID'],
                ['CON240788', 'ANURAN, KEAN DUSTIN EDUAVE'],
                ['CON230660', 'FLORES, STEPHANIE ANNE QUINITCHO'],
                ['CON240745', 'MARASIGAN, JAYDEN BAÑARIA'],
                ['CON240841', 'TENORIO, ANTONIETTE MONTALBO'],
                ['CON240805', 'OREJUDOS, LOJILLE ARGULIDA'],
                ['CON240793', 'PEREZ, KIRT JAMES AGITO'],
                ['CON240903', 'MANGUBAT, KAYE ANN SORIANO'],
                ['CON240829', 'AQUINO, MICAYLA ASHLEY RAZON'],
                ['CON241013', 'EVANGELISTA, KELLY JHYNN TOLENTINO'],
                ['CON240959', 'MALIBIRAN, SAM WAYNE SAGADRACA'],
                ['CON240875', 'ARROGANTE, CRISZELLE OLOYA'],
                ['CON241020', 'CABLAO, SHERINE ANDREI PARAS'],
                ['CON240729', 'MAGSINO, DON DON DE GUZMAN'],
                ['CON240944', 'MALINAB, JAMELLA QUEJANO'],
                ['CON241012', 'MACATANGAY, ANGEL JASMINE CONCEPCION'],
                ['CON240828', 'ARTISTA, KHEA ANDREA PEREZ'],
                ['CON240981', 'LOPEZ, JAMHELA UNTALAN'],
                ['CON240786', 'BAUTISTA, EMANUEL MULATO'],
                ['CON240897', 'DE TORRES, KRISTEL ANNE STA. TERESA'],
                ['CON240794', 'RUBIO, IRA NICOLE MICIANO'],
                ['CON240870', 'DIMAYUGA, JASMINE JEAN BAUTRO'],
                ['CON240989', 'JASA, DENISE MAE DIMALALUAN'],
            ],
            2710 => [
                ['CON240876', 'JAMAROLIN, JOHN MARC EVANGELISTA'],
                ['CON240966', 'BAQUIRAN, SARALYN AFRICA'],
                ['CON240866', 'MAGSINO, MARIJER II BALUTE'],
                ['CON240865', 'PENDEL, PRINCESS DE VILLA'],
                ['CON240807', 'CANLOBO, KIAN ASHI DULCE'],
                ['CON240752', 'CASTILLEJOS, CHESKA MAE BINAS'],
                ['CON240735', 'DE GALA, JOAN MAE SOLO'],
                ['CON240772', 'EVORA, KRISTELL JUDE CERVAN'],
                ['CON240968', 'DANSECO, JOHANNA DE TRISHA PACHECO'],
                ['CON240905', 'ADVINCULA, REYSTER TROY DE LEON'],
                ['CON240755', 'ILAGAN, PRINCESS ALEXA DIMALIBOT'],
                ['CON241021', 'GAVINO, YVONNE LAARNI CASTOR'],
                ['CON240815', 'BAGSIT, ELIESA'],
                ['CON240979', 'MANALO, KRIZZEL AQUINO'],
                ['CON240761', 'AGUDA, JOHANNA MAE AÑONUEVO'],
                ['CON240791', 'HONRADO, SAMANTHA COLEEN MENDOZA'],
                ['CON240912', 'MACALALAD, JHANA MICAELA CARINGAL'],
                ['CON240837', 'ILUSTRE, JULIANA FRANCINE HILARIO'],
                ['CON240787', 'CLARIN, CLARIZZA MAE MENDOZA'],
                ['CON240738', 'LASTIMOSA, ZYRAH KHAE VELANO'],
                ['CON240971', 'KUMMER, PAULENE NICOLE RIANO'],
                ['CON240873', 'EJES, RECIALYN MALLEN'],
                ['CON240935', 'MACARAIG, JHILIANNE DE LEON'],
                ['CON240943', 'ALBAÑEZ, JADE KRISTEL CASANOVA'],
                ['CON241019', 'ALTEZA, KIEN ZHEDRICK REYES'],
                ['CON240817', 'MACATANGAY, EMMERSON PLATA'],
                ['CON240759', 'LLARENA, HARVEY JASON DINGLASAN'],
                ['CON240717', 'BACAY, AEROLL DIMAYACYAC'],
                ['CON241014', 'MAGSINO, GHIAN NICOLE GONZALVO'],
                ['CON230368', 'ARELLANO, DEYHNIEL ANNE COLOCAR'],
                ['CON251339', 'DATINGUINOO, LIANE MARIE MAGTIBAY'],
                ['CON240958', 'BAGSIT, JOY CAPILI'],
                ['CON240899', 'BAGUI, JUDY MAE GUERRA'],
                ['CON240773', 'ARELLANO, JOHANNA CASTILLO'],
                ['CON241010', 'AZUCENA, JIAN HALE BURI'],
            ],
            2711 => [
                ['CON240749', 'ADIZ, SOFIA GRACE HERNANDEZ'],
                ['CON240877', 'PEÑAFLOR, VIA JESSICA UNTALAN'],
                ['CON240856', 'DINOPOL, DEVINE VILLANUEVA'],
                ['CON240706', 'CULTURA, CINDY DIMAANO'],
                ['CON240863', 'CEREZO, LOVELY MARIE ACLAN'],
                ['CON240825', 'SAURA, HANAH REME LEE MENDOZA'],
                ['CON240908', 'MERCADO, SHANE ROSE ZARA'],
                ['CON240893', 'VITTO, MARY JOY DITAUNON'],
                ['CON240888', 'PEREZ, JOANA ERICKA FUENTES'],
                ['CON240992', 'LALONG-ISIP, NIEL ERIC MENDOZA'],
                ['CON240693', 'DIMAANO, YURI CHRISTEL RUIZ'],
                ['CON240939', 'ACLAN, REINE ANNE TUBONGBANUA'],
                ['CON240904', 'TOLENTINO, KRISTINE MAE AZUCENA'],
                ['CON240812', 'GONZALES, ALEXIS ASHLEY ABANADOR'],
                ['CON240796', 'CANDOR, CHERRY LOU BISCOCHO'],
                ['CON240688', 'VIDAL, MARK KELLY PILIIN'],
                ['CON240810', 'GONITO, KYLA PEREZ'],
                ['CON240747', 'DATINGALING, MAE ANN BALID'],
                ['CON240926', 'ALCONEZ, PAULINE ERISELLE DELGADO'],
                ['CON240830', 'VERGARA, EUNNICE KRISTEL CATAPANG'],
                ['CON240733', 'VALDEZ, MA. LOURDES PASCUA'],
                ['CON240760', 'LICHAUCO, IVI PANALIGAN'],
                ['CON240952', 'VILLANUEVA, PATRICIA DIANNE DE TORRES'],
                ['CON230601', 'CABADING, ZYRAH ALCANTARA'],
                ['CON240867', 'MAHIYA, ANGELA VICTORIA BORILLO'],
                ['CON241003', 'DELA ROCA, DANIEL DAVEN BALDICAÑAS'],
                ['CON230583', 'FRANCISCO, ERIKA JANE DIMAYUGA'],
                ['CON230560', 'RIVERA, KYLE BERNARD JARAVATA'],
                ['CON241001', 'RAMOS, JAMES CARLO ARCEGA'],
                ['CON251291', 'SEMIRA, MA.FAITH ANGELA CAPUNO'],
                ['CON240970', 'GALLARDO, KARL JERICH EJE'],
                ['CON241004', 'MALIBIRAN, GIRL LEE MANUMBALI'],
                ['CON240906', 'MENDOZA, IRISH ELLEIN RABANO'],
                ['CON240874', 'AMUL, SHANTELLE CUETO'],
                ['CON230588', 'MAGADIA, SEDNEY ELAIZAH ALA'],
                ['CON240920', 'UMALI, JHELAI LOGRO'],
            ],
        ];

        // Section names mapped to subjid
        $sectionNames = [
            2638 => 'Section 2638',
            2706 => 'Section 2706',
            2707 => 'Section 2707',
            2708 => 'Section 2708',
            2709 => 'Section 2709',
            2710 => 'Section 2710',
            2711 => 'Section 2711',
        ];

        // ==================================================
        // 3. CREATE/GET NURSE TYPES (for students)
        // ==================================================
        $nurseTypes = [];
        $studentNurseTypeNames = [
            'ER Nurse' => 'Emergency room nursing',
            'ICU Nurse' => 'Intensive care unit nursing',
            'Ward Nurse' => 'General ward nursing',
            'OB Nurse' => 'Obstetrics and gynecology nursing',
            'Bedside' => 'Direct bedside patient care',
            'Admission' => 'Patient admission processing',
        ];
        foreach ($studentNurseTypeNames as $name => $desc) {
            $nurseTypes[$name] = NurseType::firstOrCreate(
                ['name' => $name],
                ['description' => $desc]
            )->id;
        }

        // Map station codes to nurse types (for students)
        $stationToNurseType = [
            'ER'  => 'ER Nurse',
            'ICU' => 'ICU Nurse',
            'MS'  => 'Ward Nurse',
            'OB'  => 'OB Nurse',
            'PVT' => 'Bedside',
            'OPD' => 'Bedside',
            'ADM' => 'Admission',
        ];

        // ==================================================
        // 4. CREATE UNITS AND STATIONS
        // ==================================================
        $unitStationMap = []; // [unitId => [stationCode => stationId]]

        foreach ($sectionNames as $subjid => $sectionName) {
            // Create Unit
            $unit = Unit::create([
                'name' => $sectionName,
                'description' => "Nursing Training Unit for $sectionName",
            ]);

            $unitStationMap[$unit->id] = [];

            // Create Stations for this Unit
            foreach ($stationTypes as $sType) {
                $shortCode = substr($sectionName, -4); // e.g., "2638"

                $station = Station::create([
                    'unit_id' => $unit->id,
                    'station_name' => "$sectionName - " . $sType['name'],
                    'station_code' => "$shortCode-{$sType['code']}",
                    'floor_location' => "Wing $shortCode",
                ]);

                $unitStationMap[$unit->id][$sType['code']] = [
                    'id' => $station->id,
                    'code' => $sType['code'],
                    'is_clinical' => $sType['is_clinical'],
                ];

                // Create Rooms and Beds for clinical stations with capacity
                if ($sType['cap'] > 0) {
                    $room = Room::create([
                        'station_id' => $station->id,
                        'room_number' => "$shortCode-{$sType['code']}-01",
                        'room_type' => $sType['type'],
                        'capacity' => $sType['cap'],
                        'price_per_night' => 1000,
                        'status' => 'Active',
                    ]);

                    for ($b = 1; $b <= $sType['cap']; $b++) {
                        Bed::create([
                            'room_id' => $room->id,
                            'bed_code' => "$shortCode-{$sType['code']}-01-" . chr(64 + $b),
                            'status' => 'Available',
                        ]);
                    }
                }
            }
        }

        // ==================================================
        // 5. CREATE DUMMY TEST ACCOUNTS (1 Supervisor per Unit,
        //    1 Head Nurse per Station, 1 Staff Nurse per Station)
        // ==================================================
        $dummyCounter = 1;
        $adminNurseType = NurseType::firstOrCreate(
            ['name' => 'Administrative'],
            ['description' => 'Nursing administration and management duties']
        )->id;

        foreach ($unitStationMap as $unitId => $stations) {
            $unit = Unit::find($unitId);
            $shortCode = substr($unit->name, -4);

            // A. Create Dummy Supervisor for this Unit
            $supUser = User::create([
                'name' => "Supervisor $shortCode",
                'email' => "supervisor.$shortCode@chansey.test",
                'password' => $testPassword,
                'user_type' => 'nurse',
                'badge_id' => "SUP-$shortCode",
            ]);
            Nurse::create([
                'user_id' => $supUser->id,
                'employee_id' => "SUP-$shortCode",
                'first_name' => 'Supervisor',
                'last_name' => $shortCode,
                'license_number' => "RN-SUP-$shortCode",
                'role_level' => 'Supervisor',
                'designation' => 'Clinical',
                'nurse_type_id' => $adminNurseType,
                'station_id' => null,
                'unit_id' => $unitId,
                'date_hired' => now(),
            ]);

            // B. Create Dummy Head Nurse + Staff Nurse per Station
            foreach ($stations as $stationCode => $stationData) {
                $stationId = $stationData['id'];
                $designation = ($stationCode === 'ADM') ? 'Admitting' : 'Clinical';

                // Dummy Head Nurse
                $headBadge = "HEAD-$shortCode-$stationCode";
                $headUser = User::create([
                    'name' => "Head Nurse $shortCode $stationCode",
                    'email' => strtolower("head.$shortCode.$stationCode@chansey.test"),
                    'password' => $testPassword,
                    'user_type' => 'nurse',
                    'badge_id' => $headBadge,
                ]);
                Nurse::create([
                    'user_id' => $headUser->id,
                    'employee_id' => $headBadge,
                    'first_name' => 'Head',
                    'last_name' => "$shortCode-$stationCode",
                    'license_number' => "RN-$headBadge",
                    'role_level' => 'Head',
                    'designation' => $designation,
                    'nurse_type_id' => $nurseTypes[$stationToNurseType[$stationCode]] ?? $adminNurseType,
                    'station_id' => $stationId,
                    'unit_id' => $unitId,
                    'date_hired' => now(),
                ]);

                // Dummy Staff Nurse
                $staffBadge = "STAFF-$shortCode-$stationCode";
                $staffUser = User::create([
                    'name' => "Staff Nurse $shortCode $stationCode",
                    'email' => strtolower("staff.$shortCode.$stationCode@chansey.test"),
                    'password' => $testPassword,
                    'user_type' => 'nurse',
                    'badge_id' => $staffBadge,
                ]);
                Nurse::create([
                    'user_id' => $staffUser->id,
                    'employee_id' => $staffBadge,
                    'first_name' => 'Staff',
                    'last_name' => "$shortCode-$stationCode",
                    'license_number' => "RN-$staffBadge",
                    'role_level' => 'Staff',
                    'designation' => $designation,
                    'nurse_type_id' => $nurseTypes[$stationToNurseType[$stationCode]] ?? $adminNurseType,
                    'station_id' => $stationId,
                    'unit_id' => $unitId,
                    'date_hired' => now(),
                ]);

                $dummyCounter++;
            }
        }

        // ==================================================
        // 6. IMPORT AND DISTRIBUTE STUDENTS
        // ==================================================
        // Get unit IDs in order (matching section order)
        $unitIds = array_keys($unitStationMap);
        $sectionIndex = 0;

        foreach ($studentsBySection as $subjid => $students) {
            if (!isset($unitIds[$sectionIndex])) break;

            $unitId = $unitIds[$sectionIndex];
            $stations = $unitStationMap[$unitId];
            $shortCode = substr($sectionNames[$subjid], -4);

            // Separate students: ~20% to ADM, ~80% to Clinical Stations
            $totalStudents = count($students);
            $admissionCount = max(3, (int) ceil($totalStudents * 0.15)); // At least 3, or 15%

            // Shuffle for random distribution
            shuffle($students);

            // Split students
            $admissionStudents = array_splice($students, 0, $admissionCount);
            $clinicalStudents = $students;

            // A. Create Admission Students
            $admStationId = $stations['ADM']['id'];
            foreach ($admissionStudents as $student) {
                $this->createStudentNurse(
                    $student,
                    $password,
                    'Staff',
                    'Admitting',
                    $admStationId,
                    $unitId,
                    $nurseTypes['Admission']
                );
            }

            // B. Distribute Clinical Students among ER, ICU, MS, OB, PVT, OPD
            $clinicalStations = array_filter($stations, fn($s) => $s['code'] !== 'ADM');
            $clinicalStationList = array_values($clinicalStations);
            $stationCount = count($clinicalStationList);
            $stationIdx = 0;

            foreach ($clinicalStudents as $student) {
                $targetStation = $clinicalStationList[$stationIdx];
                $stationCode = $targetStation['code'];
                $nurseTypeId = $nurseTypes[$stationToNurseType[$stationCode]] ?? $nurseTypes['Bedside'];

                $this->createStudentNurse(
                    $student,
                    $password,
                    'Staff',
                    'Clinical',
                    $targetStation['id'],
                    $unitId,
                    $nurseTypeId
                );

                // Rotate through stations
                $stationIdx = ($stationIdx + 1) % $stationCount;
            }

            $sectionIndex++;
        }

        $this->command->info('Student Import Complete!');
        $this->command->info('Total Units Created: ' . count($sectionNames));
        $this->command->info('Total Stations per Unit: ' . count($stationTypes));
        $this->command->info('Total Students Imported: ' . array_sum(array_map('count', $studentsBySection)));
    }

    /**
     * Helper: Create a Student Nurse account
     */
    private function createStudentNurse(
        array $studentData,
        string $password,
        string $roleLevel,
        string $designation,
        int $stationId,
        int $unitId,
        int $nurseTypeId
    ): void {
        $studno = $studentData[0];
        $fullName = $studentData[1];

        // Parse name: "LASTNAME, FIRSTNAME MIDDLENAME..." -> First, Last
        $nameParts = explode(',', $fullName);
        $lastName = trim($nameParts[0] ?? 'Student');
        $firstName = trim($nameParts[1] ?? $studno);

        // Create email from student number
        $email = strtolower($studno) . '@student.chansey.test';

        // Skip if user already exists
        if (User::where('email', $email)->exists()) {
            return;
        }

        $user = User::create([
            'name' => "$firstName $lastName",
            'email' => $email,
            'password' => $password,
            'user_type' => 'nurse',
            'badge_id' => $studno,
        ]);

        Nurse::create([
            'user_id' => $user->id,
            'employee_id' => $studno,
            'first_name' => $firstName,
            'last_name' => $lastName,
            'license_number' => 'STUDENT',
            'role_level' => $roleLevel,
            'designation' => $designation,
            'nurse_type_id' => $nurseTypeId,
            'station_id' => $stationId,
            'unit_id' => $unitId,
            'date_hired' => now(),
        ]);
    }
}
