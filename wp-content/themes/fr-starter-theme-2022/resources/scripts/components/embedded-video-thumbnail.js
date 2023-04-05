(function ($) {
	$.fn.addThumbnailToEmbeddedVideo = function(){
		const getHtml = (imgUrl) => {
			return `
				<div class="thumbnail-container">
					<div class="inner-overlay">
						<img src="${imgUrl}" alt="Video overlay." loading="lazy">
						<div class="play-btn" title="Click here to play the video." role="button"></div>
					</div>
				</div>
			`;
		}

		//https://gist.github.com/yangshun/9892961
		const urlParse = (url) => {
			let type = null;
			url.match(/(http:|https:|)\/\/(player.|www.)?(vimeo\.com|youtu(be\.com|\.be|be\.googleapis\.com))\/(video\/|embed\/|watch\?v=|v\/)?([A-Za-z0-9._%-]*)(\&\S+)?/);
		
			if (RegExp.$3.indexOf('youtu') > -1) {
			   type = 'youtube';
			} else if (RegExp.$3.indexOf('vimeo') > -1) {
			   type = 'vimeo';
			}
		
			return {
				type: type,
				id: RegExp.$6
			};
		}

		const onButtonClicked = ($embedContainer, $iframe, videoType) => {
			$embedContainer.find(".play-btn").on("click", (ev) => {
				ev.preventDefault();
				var src = $iframe[0].src;
				src += videoType == 'youtube' ? "&autoplay=1" : "&amp;autoplay=true";
				$iframe[0].src = src;

				$embedContainer.find(".thumbnail-container").fadeOut(300, () => {
					$embedContainer.find(".thumbnail-container").remove();
				});
			});
		}

		const initializePreview = ($self, $iframe, videoObj) => {
			let thumbnailUrl = '';

			//mark as initialized
			$self.addClass('is--initialized');

			if(videoObj.type == 'youtube'){
				thumbnailUrl = `http://img.youtube.com/vi/${videoObj.id}/maxresdefault.jpg`;
				$self.append(getHtml(thumbnailUrl));

				onButtonClicked($self, $iframe, videoObj.type);
			}
			if(videoObj.type == 'vimeo'){
				//I'm assuming the url given is a valid youtube url
				thumbnailUrl = `http://img.youtube.com/vi/${videoObj.id}/maxresdefault.jpg`;

				$.get(`http://vimeo.com/api/v2/video/${videoObj.id}.json`, (data) => {
					if(!data) return;

					thumbnailUrl = data[0].thumbnail_large;
					$self.append(getHtml(thumbnailUrl));

					onButtonClicked($self, $iframe, videoObj.type);
				});
			}
		}

		return this.each((i, el) => {
			const $self = $(el);
			const $iframe = $self.find('>iframe');
			const url = $iframe.attr('src');
			
			//if no url on the iframe, skip
			if(!url) return;

			const videoObj = urlParse(url);

			//if video not youtuve or vimeo, skip
			if(videoObj.type == null) return;

			//scroll event variable to then remove the event listener
			const onScrollEvt = () => {
				if(!$self.hasClass('is--initialized') && $.fn.isAnyPartOfElementInViewport($self[0]) ){
					initializePreview($self, $iframe, videoObj);
					//remove event listener
					$(window).off('scroll fr:pseudo-scroll', onScrollEvt);
				}
			}

			//set scroll event
			$(window).on('scroll fr:pseudo-scroll', onScrollEvt);

			//on page load
			$(window).trigger('fr:pseudo-scroll');
		});
	};

	$(() => {
		$(".video-embed").addThumbnailToEmbeddedVideo();
	});
}( jQuery ));