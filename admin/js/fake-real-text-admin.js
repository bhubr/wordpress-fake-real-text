(function( $ ) {
    'use strict';

    /**
     * Transform output from jQuery.serializeArray into a key-value map
     * https://stackoverflow.com/questions/1184624/convert-form-data-to-javascript-object-with-jquery#answer-1186309
     */
    function objectifyForm(formArray) {//serialize data function

      var returnArray = {};
      for (var i = 0; i < formArray.length; i++){
        returnArray[formArray[i]['name']] = formArray[i]['value'];
      }
      return returnArray;
    }


    // Progress bar https://codepen.io/thathurtabit/pen/ymECf
    // on page load...
    // moveProgressBar();
    // on browser resize...
    // $(window).resize(function() {
    //     moveProgressBar();
    // });

    // SIGNATURE PROGRESS
    function moveProgressBar(target, numDone, numTotal) {
        var getPercent = (1.0 * numDone) / numTotal;
        // var getPercent = (target.data('progress-percent') / 100);
        var getProgressWrapWidth = target.width();
        var progressTotal = getPercent * getProgressWrapWidth;
        var animationLength = 2500;

        // console.log("moveProgressBar", numDone, numTotal, getPercent, getProgressWrapWidth, progressTotal);

        // on page load, animate percentage bar to data percentage length
        // .stop() used to prevent animation queueing
        target.find('.progress-bar').stop().animate({
            left: progressTotal
        }, animationLength);
    }

    function startQueue(numTotal, task, done) {

        var statsContainer = $('#' + task.statsId);
        var statsList = statsContainer.find('ul');
        var statsProgress = statsContainer.find('.progress-wrap');
        console.log(statsProgress);
        statsList.empty();

        var numDone = 0;

        // create a queue object with concurrency 2
        var q = async.queue(function(task, callback) {
            $.post(ajaxurl, task.data, function(data) {
                console.log('received', data);
                callback(null, data);
            }, 'json');
        }, 1);

        // assign a callback
        q.drain = function() {
            // console.log('all items have been processed');
            moveProgressBar(statsProgress, numTotal, numTotal);
            done();
        };

        for(var i = 0 ; i < numTotal ; i++) {
            // add some items to the queue
            q.push(task, function(err, response) {
                // console.log('finished processing ' + response.post_title);
                numDone++;
                moveProgressBar(statsProgress, numDone, numTotal);
                statsList.append('<li>' + response.ID + '. ' + response.post_title + '</li>');
            });
        }
    }

    $(document).ready(function() {
        console.log($('#generate-posts'));

        $('#generate-posts').submit(function(e) {
            e.preventDefault();
            var formData = $(this).serializeArray();
            console.log(formData, objectifyForm(formData));

            var data = Object.assign(objectifyForm(formData), {
                action: 'frt_generate_posts'
            });
            var numPosts = data.num_posts;
            delete data.num_posts;

            var task = {
                data: data,
                // success: function(response) {
                //     console.log('params were', response);
                // },
                statsId: 'posts-stats'
            };

            startQueue(numPosts, task, function() {
                console.log('done');
            });

        });
    });

})( jQuery );
