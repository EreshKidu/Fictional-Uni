import $ from 'jquery';

class Like {
    constructor () {
        this.events();

    }


    events () {
        $(".like-box").on("click", this.ourClickDispatcher.bind(this) );
    }

    //methods

    ourClickDispatcher (e) {
        let currentLikeBox = $(e.target).closest(".like-box");

        if (currentLikeBox.data('exists') == 'yes'){
            this.deleteLike(currentLikeBox);
            
        } else {
            this.createLike (currentLikeBox);
        }

    }

    createLike (currentLikeBox){
        $.ajax({
            beforeSend: (xhr) => {
                xhr.setRequestHeader ('X-WP-Nonce', uniData.nonce);
            },
            url: uniData.root_url + '/wp-json/uni/v1/manageLike' ,
            type: 'POST',
            data: {
                'professorId': currentLikeBox.data('professor')
            },
            success: (response) => {
                console.log(response);
            },
            error: (response) => {
                console.log (response)
            }
        });

    }

    deleteLike (currentLikeBox){
        $.ajax({
            url: uniData.root_url + '/wp-json/uni/v1/manageLike' ,
            type: 'DELETE',
            success: (response) => {
                console.log(response);
            },
            error: (response) => {
                console.log (response)
            }
        })
        
    }
}

export default Like;