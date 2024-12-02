// выводим идентификатор
    function showLoaderIdentity(content) 
    {
        let id = "#loader-identity" + content;
        $(id).show("slow")
    }

    // скрываем идентификатор
    function hideLoaderIdentity(content) 
    {
       let id = "#loader-identity" + content;
       $(id).hide();
    }


