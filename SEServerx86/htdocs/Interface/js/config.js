function sessioncheck(page) {
    var httpReq = new XMLHttpRequest();
    var params = 'type=sessioncheck&page=index.php';

    httpReq.open('POST', '/Interface/requestjsondata.php', false);

    httpReq.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

    try {
        httpReq.send(params);
    }
    catch (e) {
        alert(e.message);
    }

    var sessiontype = httpReq.responseText;

    switch (page) {
        // 첫 화면
        case 'index.php':
            switch (sessiontype) {
                case 'mat':
                    window.location.href = "/Interface/manageindex.php";
                    break;
                case 'cook':
                    window.location.href = "/Interface/cookingstatusForm.php";
                    break;
                case 'deliver':
                    window.location.href = "/Interface/deliverystatusForm.php";
                    break;
                case 'registered':
                    window.location.href = "/Interface/loginedmainForm.php";
                    break;
                case 'unregistered':
                    window.location.href = "/Interface/ordermenuForm.php";
                    break;
                default:
                    break;
            }
            break;
        // 등록 화면
        case 'registerForm.php':
            if (sessiontype !== 'unlogined') {
                window.location.href = "/Interface/index.php";
            }
            break;
        // 등록된 회원이 로그인 한 경우
        case 'loginedmainForm.php':
        case 'updatemembershipForm.php':
        case 'orderedmenulistForm.php':
            if (sessiontype !== 'registered') {
                window.location.href = "/Interface/index.php";
            }
            break;
        // 멧이 로그인 
        // 원재료 관리 화면  
        case 'updatesuppliesinfoForm.php':
        // 메뉴 및 코스요리 관리 화면  
        case 'updatemenuinfoForm.php':
        // 할인 정책 관리 화면  
        case 'discountpolicyForm.php':
        case 'manageindex.php':
            if (sessiontype !== 'mat') {
                window.location.href = "/Interface/index.php";
            }
            break;
        // 주문 창 - 비회원 로그인일 경우 이곳으로
        case 'ordermenuForm.php':
        case 'ordercompleteForm.php':
            if (sessiontype !== 'registered' && sessiontype !== 'unregistered') {
                window.location.href = "/Interface/index.php";
            }
            break;
        // 요리 직원이 로그인한 경우
        case 'cookingstatusForm.php':
            if (sessiontype !== 'cook') {
                window.location.href = "/Interface/index.php";
            }
            break;
        // 배달 직원이 로그인한 경우
        case 'deliverystatusForm.php':
            if (sessiontype !== 'deliver') {
                window.location.href = "/Interface/index.php";
            }
            break;
    }
}

function requestserver(reqtype) {
    var httpReq = new XMLHttpRequest();
    var params = 'type='+reqtype;

    httpReq.open('POST', '/Interface/requestjsondata.php', false);

    httpReq.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

    try {
        httpReq.send(params);
    }
    catch (e) {
        alert(e.message);
    }

    var jsonobject = httpReq.responseText;

    return jsonobject;
}
    
