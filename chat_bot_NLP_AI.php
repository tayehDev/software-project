<?php
include 'config.php';

//=======================================================
// كلمات للتأكيد (Positive / Affirmative)
$confirm_terms = [
    " ممتاز "," حسنا ", " جيد ", " تمام ", " افضل ", " رائع ", " جميل ", " لطيف ", " حلو ",
    " بسيط ", " منطقي ", " طبيعي ", " واضح ", " سريع ", " سهل ", " مؤكد ", " ناجح ",
    " دقيق ", " شامل ", " واسع ", " مهم ", " كافي ", " رائع جدا ", " جيد جدا ",
    " ممتاز فعلا ", " طبيعي تماما ", " معقول ", " ملفت ", " جذاب ", " مناسب ",
    " مفيد ", " رائع للغاية ", " مدهش ", " مذهل ", " بسيط للغاية ",
    // English equivalents
    " excellent "," ok ", " good ", " better ", " great ", " nice ", " beautiful ", " awesome ",
    " simple ", " logical ", " clear ", " fast ", " easy ", " sure ", " successful ",
    " accurate ", " wide ", " important ", " enough ", " cool ", " amazing ", " lovely ",
    " incredible ", " awesome ", " useful "
];


// كلمات للنفي (Negative / Negation)
$negation_terms = [
    // كلمات عربية سلبية
    "سيء","كلا", "فاشل", "صعب","لم افهم", "غير منطقي", "غير مناسب", "غير مفيد", 
    "غير ممتاز", "غير جيد", "ليس تمام", "ليس افضل", "ليس رائع", "ليس جميل", 
    "ليس لطيف", "ليس حلو", "ليس بسيط", "غير طبيعي", "غير واضح", "ليس سريع", 
    "ليس سهل", "غير مؤكد", "غير ناجح", "غير دقيق", "غير شامل", "ليس واسع", 
    "غير مهم", "غير كافي", "ليس رائع جدا", "ليس جيد جدا", "ليس ممتاز فعلا", 
    "ليس طبيعي تماما", "غير معقول", "ليس ملفت", "ليس جذاب", "ليس رائع للغاية", 
    "ليس مدهش", "ليس مذهل", "ليس بسيط للغاية",

    // الكلمات الإنجليزية
    "horrible","no","not", "bad", "failed", "hard", "different", "useless","not understand",
    "not excellent", "not good", "not better", "not great", "not nice", "not beautiful", 
    "not awesome", "not simple", "not logical", "not clear", "not fast", "not easy", 
    "not sure", "not successful", "not accurate", "not wide", "not important", 
    "not enough", "not cool", "not amazing", "not lovely", "not incredible", 
    "not useful"
];

    
// دوال مساعدة
//=======================================================

/**
 * تحقق من تشابه نصين بنسبة محددة
 */
function check_like($a, $b, $limit = 70) {
    similar_text($a, $b, $rate);
    return $rate >= $limit;
}

/**
 * استخراج الكلمات المهمة من نص مع إزالة الكلمات العامة والأدوات
 */
function extract_txt($input) {
    $content = " " . trim($input) . " ";

    // قوائم الكلمات العامة والأدوات باللغتين العربية والإنجليزية
    $preps = [" في ", " على ", " من ", " الى ", " عن ", " حتى ", " عبر ", " خلال ", 
              " نحو ", " ضد ", " ب ", " ل ", " ك ", " بدون ", " وسط ", " تجاه ", " قرب ",
              " in ", " on ", " from ", " to ", " about ", " through ", " toward ", " against ", " without ", " near ", " beside "];
    
    $signal_words = [" هذا ", " هذه ", " هذان ", " هاتان ", " هؤلاء ", " ذلك ", " تلك ",
                     " ذاك ", " اولئك ",
                     " this ", " that ", " these ", " those ", " such "];

    $connectors = [" و", " ف", " ثم ", " او ", " ام ", " لا ", " لكن ", " حتى ", " بل ", 
                   " كذلك ", " ايضا ", " علاوة ", " فضلا ",
                   " and ", " or ", " but ", " yet ", " also ", " moreover ", " besides ", " furthermore "];

    $linked_nouns = [" الذي ", " التي ", " اللذان ", " اللتان ", " الذين ", " اللواتي ", " اللائي ",
                     " which ", " who ", " whom ", " whose ", " that "];

    $pron_refs = [" انا ", " انت ", " انتي ", " انتم ", " انتن ", " هو ", " هي ",
                  " هم ", " هن ", " نحن ",
                  " it "," i ", " you ", " he ", " she ", " we ", " they "," their ", " me ", " us ", " them ", " my ", " your ", " our "];

    $question_parts = [" هل ", " ما ", " ماذا ", " متى ", " كيف ", " اين ", " كم ", " لماذا ", " بماذا ",
                       " what ", " when ", " where ", " why ", " how ", " which ", " who ", " can ", " can you ", " did you ", " are you ", " do you ", " would you ", " will you ", " shall you ", " should you "];

    $conditionals = [" اذا ", " لو ", " لولا ", " كلما ", " مهما ", " من ", " اينما ", " حيثما ", " متى ", " ايان ",
                     " if ", " when ", " unless ", " whenever ", " whether "];

    $exceptions = [" الا ", " غير ", " سوى ", " خلا ", " عدا ", " حاشا ",
                   " except ", " unless ", " other than ", " besides ", " apart from "];
                   

    $emphasis = [" ان ", " انما ", " قد ", " حقا ", " بالفعل ", " لعل ", " لام التوكيد ", " انني ",
                 " indeed ", " really ", " actually ", " certainly ", " truly ", " definitely "];

    $causals = [" لان ", " لكي ", " من اجل ", " بسبب ", " نظرا ", " حتى ", " كي ", " حيث ",
                " because ", " since ", " due to ", " therefore ", " as ", " for ", " so that "];

    $circumstances = [" هنا ", " هناك ", " الان ", " حين ", " قبل ", " بعد ", " دائما ", " حاليا ",
                      " قريبا ", " بعيدا ", " فوق ", " تحت ", " خلف ", " امام ", " جانب ", " مع ", " بدون ",
                      " here ", " there ", " now ", " before ", " after ", " always ", " soon ", " later ", " near ", " far ", " beside ", " above ", " below ", " behind ", " in front "];

    $weak_verbs = [" كان ", " اصبح ", " ظل ", " صار ", " بات ", " ليس ", " ما زال ", " ما دام ",
                   " am "," is ", " was ", " were ", " become ", " seem ", " appear ", " remain "];

    $compare_words = [" مثل ", " كمثل ", " كأنه ", " كما ", " يشبه ", " يشابه ", " كأن ",
                      " like ", " as ", " similar ", " resembles ", " looks like "];

    // كلمات للتأكيد (Positive / Affirmative)
    $confirm_terms = $GLOBALS['confirm_terms'];

    // كلمات للنفي (Negative / Negation)
    $negation_terms = $GLOBALS['negation_terms'];;

    // كلمات عامة أخرى (Neutral / General)
    $general_terms = [
        " شيء ", " ايضا ", " مقبول ", " كثير ", " قليل ", " ارغب ", " اتمنى ", " اريد ",
        " اعتبر ", " افكر ", " اجزم ", " ااكد ", " اقول ", " اهم ", " غريب ", " معقد ",
        " عادي ", " غامض ", " بطيء ", " محتمل ", " مختلف ", " متشابه ", " جديد ", " قديم ",
        " تقليدي ", " مميز ", " فعلي ", " حقيقي ", " مثالي ", " مؤقت ", " دائم ", " محدود ",
        " temporary ", " permanent ", " normal ", " natural ", " vague ", " slow ", " possible ",
        " similar ", " new ", " old ", " unique ", " special "
    ];


    // تطبيع الحروف
    $normalize = ["أ" => "ا", "إ" => "ا", "آ" => "ا"];
    $content = str_replace(array_keys($normalize), array_values($normalize), $content);
    $content = preg_replace('/\bال(?=\p{L})/u', '', $content); // إزالة "ال" في بداية الكلمات

    // حذف المجموعات
    $removals = [$signal_words, $linked_nouns, $connectors, $preps, $pron_refs,
                 $question_parts, $conditionals, $exceptions, $emphasis,
                 $causals, $circumstances, $weak_verbs, $compare_words, $confirm_terms, $negation_terms, $general_terms];

    foreach ($removals as $group) {
        $content = str_replace($group, " ", $content);
    }

    // تنظيف النص من التشكيل والرموز
    $content = preg_replace('/[ًٌٍَُِّْـ]/u', '', $content);
    $content = preg_replace('/[^\p{L}\p{N}\s]/u', '', $content);

    // تقسيم النص إلى مقاطع
    $segments = preg_split('/\s+/u', trim($content));
    $segments = array_filter($segments, fn($s) => mb_strlen($s) > 2);
    $segments = array_unique($segments);

    // إزالة الكلمات المتشابهة بنسبة 70%
    $finalList = [];
    foreach ($segments as $term) {
        $isDistinct = true;
        foreach ($finalList as $i => $old) {
            if (check_like($term, $old, 70)) {
                $finalList[$i] = (mb_strlen($term) < mb_strlen($old)) ? $term : $old;
                $isDistinct = false;
                break;
            }
        }
        if ($isDistinct) $finalList[] = $term;
    }

    return $finalList;
}

//=======================================================
// استقبال نص المستخدم
//=======================================================
$jsonBack = [];

$received_txt = $_REQUEST['received_txt'] ?? '';
if (empty(trim($received_txt))) {
    echo json_encode(['status' => 'error', 'contentMsg' => 'Please Write Your Question !!']);
    exit();
}

// تحقق إن كان النص يحتوي على حروف عربية
$received_txt = trim($received_txt);
if (preg_match('/\p{Arabic}/u', $received_txt)) {
    $language = 'arabic';
} else {
    $language = 'english';
}

foreach ($confirm_terms as $term) {
    similar_text($received_txt, trim($term), $percent);

    if ($percent >= 70) {
        echo json_encode([
            'status' => 'ok',
            'contentMsg' => $language == 'english' ? 'very nice, i am here to help you' : 'حسنا انا مستعد لاي استفسار او مساعدة'
        ]);
        exit();
    }
}
foreach ($negation_terms as $term) {
    similar_text($received_txt, trim($term), $percent);

    if ($percent >= 70) {
        echo json_encode([
            'status' => 'ok',
            'contentMsg' => $language == 'english' ? 'i am sorry, how can i help you or please rewrite you question in another form?' : 'اعتذر ان لم اقدم لك اجابة مفيدة اعد السؤال بطريقة اخرى من فضلك؟'
        ]);
        exit();
    }
}

        

// استخراج الرموز المهمة
$inputTokens = extract_txt($received_txt);
if (empty($inputTokens)) {
    echo json_encode(['status' => 'error', 'contentMsg' =>$language == 'english' ? 'sorry i dont understand ' : 'عذرا منك .. لم افهم الجملة']);
    exit();
}

//=======================================================
// جلب الأسئلة من قاعدة البيانات
//=======================================================
$where="";
if(isset($_POST['seller_user_id'])&&!empty($_POST['seller_user_id']))$where.=" AND auto_chat_seller_user_id=$_POST[seller_user_id]";
$query = "SELECT `id`, `question`, `answer` FROM `chat_conjunction` WHERE 1 $where";
$stmt = $connect->prepare($query);
$stmt->execute();
$results = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

$most_similar = 0;

//=======================================================
// حساب درجة التشابه
//=======================================================
foreach ($results as &$row) {
    $contentTokens = extract_txt($row['question']);
    $similarityScore = 0;

    foreach ($inputTokens as $inputToken) {
        foreach ($contentTokens as $contentToken) {
            similar_text($inputToken, $contentToken, $percent);
            if ($percent > 90) { $similarityScore += 4; break; }
            else if ($percent > 80) { $similarityScore += 3; break; }
            else if ($percent > 70) { $similarityScore += 2; break; }
            else if ($percent > 60) { $similarityScore += 1; break; }
        }
    }
    $row['similarity'] = $similarityScore;
    if ($similarityScore > $most_similar) $most_similar = $similarityScore;
}

//=======================================================
// إذا لم نجد تطابق في الأسئلة، نقارن بالإجابات
//=======================================================
if ($most_similar == 0) {
    foreach ($results as &$row) {
        $contentTokens = extract_txt($row['answer']);
        $similarityScore = 0;

        foreach ($inputTokens as $inputToken) {
            foreach ($contentTokens as $contentToken) {
                similar_text($inputToken, $contentToken, $percent);
                if ($percent > 90) { $similarityScore += 4; break; }
                else if ($percent > 80) { $similarityScore += 3; break; }
                else if ($percent > 70) { $similarityScore += 2; break; }
                else if ($percent > 60) { $similarityScore += 1; break; }
            }
        }
        $row['similarity'] = $similarityScore;
        if ($similarityScore > $most_similar) $most_similar = $similarityScore;
    }
}

//=======================================================
// ترتيب النتائج واختيار الإجابة الأفضل
//=======================================================
usort($results, fn($a, $b) => $b['similarity'] <=> $a['similarity']);

if (!empty($results) && $most_similar > 0) {
    echo json_encode(['status' => 'ok', 'contentMsg' => $results[0]['answer']]);
} else {
    echo json_encode(['status' => 'error', 'contentMsg' => $language == 'english' ? 'sorry i dont understand ' : 'عذرا منك .. لم افهم الجملة']);
}

exit();

?>

