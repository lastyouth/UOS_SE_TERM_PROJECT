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
        // ù ȭ��
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
        // ��� ȭ��
        case 'registerForm.php':
            if (sessiontype !== 'unlogined') {
                window.location.href = "/Interface/index.php";
            }
            break;
        // ��ϵ� ȸ���� �α��� �� ���
        case 'loginedmainForm.php':
        case 'updatemembershipForm.php':
        case 'orderedmenulistForm.php':
            if (sessiontype !== 'registered') {
                window.location.href = "/Interface/index.php";
            }
            break;
        // ���� �α��� 
        // ����� ���� ȭ��  
        case 'updatesuppliesinfoForm.php':
        // �޴� �� �ڽ��丮 ���� ȭ��  
        case 'updatemenuinfoForm.php':
        // ���� ��å ���� ȭ��  
        case 'discountpolicyForm.php':
        case 'manageindex.php':
            if (sessiontype !== 'mat') {
                window.location.href = "/Interface/index.php";
            }
            break;
        // �ֹ� â - ��ȸ�� �α����� ��� �̰�����
        case 'ordermenuForm.php':
        case 'ordercompleteForm.php':
            if (sessiontype !== 'registered' && sessiontype !== 'unregistered') {
                window.location.href = "/Interface/index.php";
            }
            break;
        // �丮 ������ �α����� ���
        case 'cookingstatusForm.php':
            if (sessiontype !== 'cook') {
                window.location.href = "/Interface/index.php";
            }
            break;
        // ��� ������ �α����� ���
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
    
