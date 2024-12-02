$(document).ready(function()
{
    $(".loadArticle").bind("click", function(){
        let articleId = $(this).attr('data-contentId');
        $.ajax({
            
            url:"ajax/loadArticle.php",
            type:"POST",
            data:({articleId: articleId}),
            dataType:"html",
            beforeSend: function()
            {
                let id = "#article" + articleId;
                $(id).text("Загрузка данных");
            },
            success: function (data)
            {
                let id = "#article" + articleId;
                $(id).text(data);
            },
            error: function funcError()
            {
                let id = "#article" + articleId;
                $(id).text("Ошибка!");
            }
        });
    });
});