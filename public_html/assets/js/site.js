class Pages {
	constructor() {
		$('#logout').click(e => {
			e.preventDefault()

			$.ajax({
				type: "POST",
				url: '/api.php',
				data: {
					type: 'logout',
				},
				success: function(response)
				{
					var jsonData = JSON.parse(response)

					if (jsonData.success == "1") {
						window.location.href = ""
					} else {
						swal("Whoops!","Something went wrong when logging you out!","error")
					}
				}
			})
		})
	}

	materialRipples() {
		const selector = '.mdc-button, .mdc-icon-button, .mdc-card__primary-action';
		const ripples = [].map.call(document.querySelectorAll(selector), function(el) {
			return mdc.ripple.MDCRipple.attachTo(el)
		})
	}

	materialSwitches() {
		[].slice.call(document.querySelectorAll('.mdc-switch')).forEach((ele)=>{
			mdc.switchControl.MDCSwitch.attachTo(ele)
		})
	}

	stripHTML(dirtyString) {
		return dirtyString.replace(/(<([^>]+)>)/ig,"")
	}

	characterCounter() {
		var bodyDIV = $('iframe').contents().find("body")
		bodyDIV.bind('DOMSubtreeModified',()=>{
			var count = this.stripHTML(bodyDIV.html()).length
			$('#chars').html( count )
			if(count > 2000 || count < 1) {
				$('#chars').addClass("text-danger")
				$('#chars').addClass("font-weight-bold")
			} else {
				$('#chars').removeClass("text-danger")
				$('#chars').removeClass("font-weight-bold")
			}
		})
	}
}

class HomePage extends Pages {
	curpage = 1
	numpages = 0

	constructor() {
		super()

		this.loadCards()

		this.materialSwitches()

		var nextScrollAction = 0
		$(window).on('scroll',()=>{
			// Antispam with delay in seconds
			if( nextScrollAction > (new Date()).getTime() ) return

			// Scrolling down, load downward
			if ($(window).scrollTop() >= $('#load_more').offset().top + $('#load_more').outerHeight() - window.innerHeight && this.curpage+1 <= this.numpages) {
				nextScrollAction = (new Date()).getTime()+.4*1000

				this.curpage = this.curpage + 1
				this.loadCards()
			}
		})

		// Welcomer Stuff
		$('#welcomer').change((e)=>{
			var newThing = 0;
			var state = $('#switchActual').hasClass("mdc-switch--checked")
			if(state)
				newThing = 1
			else
				newThing = 0

			$.ajax({
				type: "POST",
				url: '/api.php',
				data: {
					type: 'welcome',
					new: newThing,
				},
				success: function(response)
				{
					var jsonData = JSON.parse(response)

					if (jsonData.success == "1") {
						if(state) {
							swal("Success!","You will now be welcomed!","success")
						} else {
							swal("Success!","You will no longer be welcomed!","success")
						}
					} else {
						swal("Whoops!","Something went wrong when changing your welcome toggle!","error")
					}
				}
			})
		})
	}

	loadCards() {
		if(this.curpage > 1) {
			$('#postAfterSpinner').removeClass('d-none')
			$('#postAfterSpinner').addClass('d-flex')
		}

		var _this = this

		$.ajax({
			type: "POST",
			url: '/api.php',
			data: {
				type: 'loadposts',
				curpage: this.curpage,
			},
			success: function(response)
			{
				var jsonData = JSON.parse(response);

				if (jsonData.success == "1") {
					$('#postStartSpinner').removeClass('d-flex')
					$('#postStartSpinner').addClass('d-none')

					$('#postAfterSpinner').addClass('d-none')
					$('#postAfterSpinner').removeClass('d-flex')

					if(jsonData.data.length > 0) {
						var cards = ""

						_this.numpages = jsonData.numpages

						jsonData.data.forEach((v,k) => {
							var templateCard = $('#templateCard').html()

							var marg = "mb-3"
							if(_this.curpage == _this.numpages && k == jsonData.data.length-1)
								marg = ""

							templateCard = templateCard.replace("{$marg}",marg)

							templateCard = templateCard.replace(`{$value["title"]}`,v.title)

							templateCard = templateCard.replace(`{$value["body"]}`,v.body)

							templateCard = templateCard.replace(` d-none`,"")

							templateCard = templateCard.replace(`A few moments ago`,v.date)

							templateCard = templateCard.replace(`the_ID`,v.id)

							cards = cards+templateCard
						})

						$('#posts').append(cards)
						$('#posts').fadeOut(0)
						$('#posts').fadeIn()

						// Make the dates work
						$("abbr.timeago").timeago()

						// Make the material stuff work
						_this.materialRipples()
					} else {
						$('#posts').html(`
							<div class="jumbotron">
								<h1 class="display-4">Nothing here!</h1>
								<p class="lead">There are no posts available right now!</p>
							</div>
						`)
					}
				} else {
					swal("Content Unavailable!","Please try again in a few moments.","error")
				}
			}
		})
	}
}

class LoginPage extends Pages {
	constructor() {
		super()

		$('#loginform').submit(e=>{
			e.preventDefault()

			$.ajax({
				type: "POST",
				url: '/api.php',
				data: {
					type: 'login',
					user: $('#usernamefield').val(),
					pass: $('#passfield').val(),
				},
				success: function(response)
				{
					var jsonData = JSON.parse(response);

					if (jsonData.success == "1") {
						if(jsonData.valid == 1) {
							window.location.href = "/"
						} else {
							$('#invaliduserpass').removeClass("d-none")
							$('#invaliduserpass').fadeOut(0)
							$('#invaliduserpass').fadeIn()
						}
					} else {
						swal("Content Unavailable!","Please try again in a few moments.","error")
					}
				}
			})
		})
	}
}

class CreatePost extends Pages {
	constructor() {
		super()

		editor.document.designMode = "On"

		this.characterCounter()

		$('#submit').click(e => {
			e.preventDefault()

			var bodyDIV = $('iframe').contents().find("body")

			var body = bodyDIV.html()
			var title = $('#title').val()

			var xhr = new XMLHttpRequest()
			xhr.open('POST','/api.php',true)
			xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded;')
			xhr.onload = function() {
				if (xhr.status === 200) {
					var jsonData = JSON.parse(xhr.responseText)

					if (jsonData.success == "1") {
						swal("Success!","Your post was created!","success")
						  .then(()=>{
							window.location.href = "/formpost.php?id="+jsonData.postID
						  })
					} else {
						if(jsonData.msg)
							swal("Whoops!",jsonData.msg,"error")
						else
							swal("Whoops!","Something went wrong when posting your thread!","error")
					}
				}
			}
			xhr.send('type=post&title='+title+'&body='+body)
		})
	}
}

class EditPost extends Pages {
	constructor() {
		super()

		this.characterCounter()

		editor.document.designMode = "On"

		$('iframe').contents().find("body").html($('#body').html())

		$('#submit').click(e => {
			e.preventDefault()

			var bodyDIV = $('iframe').contents().find("body")

			var body = bodyDIV.html()
			var title = $('#title').val()

			var postID = $('#postID').html()

			$.ajax({
				type: "POST",
				url: '/api.php',
				data: {
					type: 'edit',
					title: title,
					body: body,
					id: postID,
				},
				success: function(response)
				{
					var jsonData = JSON.parse(response)

					if (jsonData.success == "1") {
						swal("Success!","Your post was edited!","success")
						  .then(()=>{
						  	window.location.href = "/formpost.php?id="+postID
						  })
					} else {
						if(jsonData.msg)
							swal("Whoops!",jsonData.msg,"error")
						else
							swal("Whoops!","Something went wrong when posting your thread!","error")
					}
				}
			})
		})
	}
}

class PostPage extends Pages {
	constructor() {
		super()

		$('#deletePost').click(e => {
			e.preventDefault()

			var postID = $('#postID').html()

			swal({
				title: "Are you sure?",
				text: "Once deleted, you will not be able to recover this post!",
				icon: "warning",
				buttons: true,
				dangerMode: true,
			}).then((willDelete) => {
				if (willDelete) {
					$.ajax({
						type: "POST",
						url: '/api.php',
						data: {
							type: 'delete',
							id: postID,
						},
						success: function(response)
						{
							var jsonData = JSON.parse(response)

							if (jsonData.success == "1") {
								swal("Success!","Your post was deleted!","success")
								  .then(()=>{
								  	window.location.href = "/"
								  })
							} else {
								swal("Whoops!","There was an error deleting your post!","error")
							}
						}
					})
				}
			})
		})
	}
}

class ContactPage extends Pages {
	constructor() {
		super()

		$('#contactform').submit((e)=>{
			e.preventDefault()

			var name = $('#name').val()
			var body = $('#body').val()

			$.ajax({
				type: "POST",
				url: '/api.php',
				data: {
					type: 'contactform',
					name: name,
					body: body,
				},
				success: function(response)
				{
					var jsonData = JSON.parse(response)

					if (jsonData.success == "1") {
						swal("Thank You!","You've sent us a message and we'll get back to you as soon as Covid-19 is over..","success")
						  .then(()=>{
						  	window.location.href = "/"
						  })
					} else {
						swal("Whoops!","Something went wrong when sending your message!","error")
					}
				}
			})
		})
	}
}

$(document).ready(()=>{
	var daPage = $('.pageIndicator').attr('page')
	switch(daPage) {
		case 'home':
			new HomePage()
			break
		case 'login':
			new LoginPage()
			break
		case 'post':
			new CreatePost()
			break
		case 'editpost':
			new EditPost()
			break
		case 'formpost':
			new PostPage()
			break
		case 'contactus':
			new ContactPage()
			break
		default:
			new Pages()
	}
})

// For rich text editor
function transform(option, argument) {
	editor.document.execCommand(option, false, argument)
}