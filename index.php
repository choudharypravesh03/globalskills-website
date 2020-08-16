<?php

function ip_info($ip = NULL, $purpose = "location", $deep_detect = TRUE) {
    $output = NULL;
    if (filter_var($ip, FILTER_VALIDATE_IP) === FALSE) {
        $ip = $_SERVER["REMOTE_ADDR"];
        if ($deep_detect) {
            if (filter_var(@$_SERVER['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP))
                $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
            if (filter_var(@$_SERVER['HTTP_CLIENT_IP'], FILTER_VALIDATE_IP))
                $ip = $_SERVER['HTTP_CLIENT_IP'];
        }
    }
    $purpose    = str_replace(array("name", "\n", "\t", " ", "-", "_"), NULL, strtolower(trim($purpose)));
    $support    = array("country", "countrycode", "state", "region", "city", "location", "address");
    $continents = array(
        "AF" => "Africa",
        "AN" => "Antarctica",
        "AS" => "Asia",
        "EU" => "Europe",
        "OC" => "Australia (Oceania)",
        "NA" => "North America",
        "SA" => "South America"
    );
    if (filter_var($ip, FILTER_VALIDATE_IP) && in_array($purpose, $support)) {
        $ipdat = @json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=" . $ip));
        if (@strlen(trim($ipdat->geoplugin_countryCode)) == 2) {
            switch ($purpose) {
                case "location":
                    $output = array(
                        "city"           => @$ipdat->geoplugin_city,
                        "state"          => @$ipdat->geoplugin_regionName,
                        "country"        => @$ipdat->geoplugin_countryName,
                        "country_code"   => @$ipdat->geoplugin_countryCode,
                        "continent"      => @$continents[strtoupper($ipdat->geoplugin_continentCode)],
                        "continent_code" => @$ipdat->geoplugin_continentCode
                    );
                    break;
                case "address":
                    $address = array($ipdat->geoplugin_countryName);
                    if (@strlen($ipdat->geoplugin_regionName) >= 1)
                        $address[] = $ipdat->geoplugin_regionName;
                    if (@strlen($ipdat->geoplugin_city) >= 1)
                        $address[] = $ipdat->geoplugin_city;
                    $output = implode(", ", array_reverse($address));
                    break;
                case "city":
                    $output = @$ipdat->geoplugin_city;
                    break;
                case "state":
                    $output = @$ipdat->geoplugin_regionName;
                    break;
                case "region":
                    $output = @$ipdat->geoplugin_regionName;
                    break;
                case "country":
                    $output = @$ipdat->geoplugin_countryName;
                    break;
                case "countrycode":
                    $output = @$ipdat->geoplugin_countryCode;
                    break;
            }
        }
    }
    return $output;
}
//echo ip_info("Visitor", "Country Code");
if(ip_info("Visitor", "Country Code")=="CA")
{
?>

<!DOCTYPE html>
<html lang="en">

	<head>
		
<!-- Facebook Pixel Code -->
<script>
  !function(f,b,e,v,n,t,s)
  {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
  n.callMethod.apply(n,arguments):n.queue.push(arguments)};
  if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
  n.queue=[];t=b.createElement(e);t.async=!0;
  t.src=v;s=b.getElementsByTagName(e)[0];
  s.parentNode.insertBefore(t,s)}(window, document,'script',
  'https://connect.facebook.net/en_US/fbevents.js');
  fbq('init', '859990684360389');
  fbq('track', 'PageView');
</script>
<noscript><img height="1" width="1" style="display:none"
  src="https://www.facebook.com/tr?id=859990684360389&ev=PageView&noscript=1"
/></noscript>
<!-- End Facebook Pixel Code -->

<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-134031783-3"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-134031783-3');
</script>



		<!-- COMMON TAGS -->
<meta charset="utf-8">
<!-- Search Engine -->
<meta name="description" content="Making global hiring easy with our pool of skilled, vetted talent, ready to relocate to Canada.">
<meta name="image" content="http://www.globalskills.io/app/images/shareimg.jpg">
<!-- Schema.org for Google -->
<meta itemprop="name" content="Global Skills Hub">
<meta itemprop="description" content="Making global hiring easy with our pool of skilled, vetted talent, ready to relocate to Canada.">
<meta itemprop="image" content="http://www.globalskills.io/app/images/shareimg.jpg">
<!-- Open Graph general (Facebook, Pinterest & Google+) -->
<meta name="og:title" content="Global Skills Hub">
<meta name="og:description" content="Making global hiring easy with our pool of skilled, vetted talent, ready to relocate to Canada.">
<meta name="og:image" content="http://www.globalskills.io/app/images/shareimg.jpg">
<meta name="og:url" content="http://www.globalskills.io">
<meta name="og:site_name" content="Global Skills Hub">
<meta name="og:locale" content="en_CA">
<meta name="og:type" content="website">
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta http-equiv="X-UA-Compatible" content="ie=edge">
		<link rel="icon" href="app/images/icon.png" type="image/x-icon" />
		<title>Home | Global Skills Hub</title>
		<link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
		<link rel="stylesheet" type="text/css"
			href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css" />
		<link rel="stylesheet" href="app/css/style.min.css">
	</head>

	<body>
		<header>
			<div class="container">
				<div class="flex items-align-center space-between">
					<div class="navbar-brand">
						<a href="/">
							<img src="app/images/logo.png">
						</a>
						<div class="toggle-btn">
							<span class="menu-item"></span>
							<span class="menu-item"></span>
							<span class="menu-item"></span>
						</div>
					</div>
					<div class="navigation">
						<ul>
							<li><a href="faq.html">FAQ</a></li>
							<li><a href="/blog">Blog</a></li>
							<li><a href="about.html">About</a></li>
							<li><a class="scroll-btn" href="#contactSection">Contact</a></li>
						</ul>
						<ul class="social-icon">
							<li><a target="_blank" href="https://www.linkedin.com/company/get-global-skills"><i class="fa fa-linkedin" aria-hidden="true"></i></a></li>
							<li><a target="_blank" href="#"><i class="fa fa-instagram" aria-hidden="true"></i></a></li>
							<li><a target="_blank" href="#"><i class="fa fa-facebook" aria-hidden="true"></i></a></li>
							<li><a target="_blank" href="#"><i class="fa fa-twitter" aria-hidden="true"></i></a></li>
						</ul>
					</div>
				</div>
			</div>
		</header>
		<div class="banner-section" style="background-image: url(app/images/banner-img.jpg)">
			<div class="container">
				<div class="banner-text color-white">
					<h1 data-aos="fade-up">Your global tech recruiting made easy</h1>
					<p data-aos="fade-up" data-aos-delay="200">We'll help you with the entire hiring process for highly-skilled, ready-to-relocate tech candidates, with expedited visa approval times</p>
					<ul data-aos="fade-up" data-aos-delay="400">
						<li><a href="javascript:void(0)" class="btn popup-btn">Find talent</a></li>
						<li><a href="candidate.html">I'm looking for a job</a></li>
					</ul>
				</div>
				<div class="trusted-section text-center">
					<p>Trusted by:</p>
					<ul>
						<li><img src="app/images/client-1.png"></li>
						<li><img src="app/images/kik-logo.png"></li>
						<li><img src="app/images/client-3.png"></li>
						<li><img src="app/images/client-4.png"></li>
						<li><img src="app/images/client-5.png"></li>
					</ul>
				</div>
			</div>
		</div>
		<main>
			<div class="work-section text-center section-lg">
				<div class="container">
					<h2 data-aos="fade-up">What We Do</h2>
					<div class="flex space-between">
						<div class="colmn-box" data-aos="fade-up">
							<figure>
								<img src="app/images/work-icon-1.png">
							</figure>
							<h6>Find you H1-B Holders</h6>
							<p>We have a pool of highly experienced tech workers in the US that are looking for
								opportunities in Canada. We have also built a pool of international candidates with 5+
								years’ experience working for North American companies.</p>
						</div>
						<div class="colmn-box" data-aos="fade-up" data-aos-delay="200">
							<figure>
								<img src="app/images/work-icon-2.png">
							</figure>
							<h6>Save you time and Money</h6>
							<p>Let our team do the time-consuming stuff so you can focus on filling roles with the best
								people. Plus, we have flexible payment options including monthly. <a href="faq.html">Find out more on the
								FAQ</a>.</p>
						</div>
						<div class="colmn-box" data-aos="fade-up" data-aos-delay="300">
							<figure>
								<img src="app/images/work-icon-3.png">
							</figure>
							<h6>Remove risk</h6>
							<p>Use our "try-before-you-hire" system to hire the candidate remotely. We coordinate the
								payment of contracting fees during the trial period. We remain engaged once the
								placement is made, and fully support the candidate and company to ensure successful
								relocation.</p>
						</div>
						<div class="colmn-box" data-aos="fade-up" data-aos-delay="400">
							<figure>
								<img src="app/images/work-icon-4.png">
							</figure>
							<h6>Manage Visa Processing</h6>
							<p>We work with our immigration team at Green and Spiegel LLP to make the legal process
								seamless, and can even manage complicated immigration issues.</p>
						</div>
					</div>
				</div>
			</div>
			<div class="strip-section text-center bg-control color-white"
				style="background-image: url(app/images/strip-bg.png)">
				<h3>Ready to start finding talent?</h3>
				<a href="javascript:void(0)" class="btn popup-btn">Find talent</a>
			</div>
			<div class="relocate-section gray-bg section-lg text-center">
				<div class="container">
					<h2 data-aos="fade-up">Ready to Relocate</h2>
					<p data-aos="fade-up" data-aos-delay="200">All of our candidates are highly-skilled, have taken
						relevant programming tests, speak fluent English, and are ready to relocate to Canada
						immediately.</p>
					<div class="flex space-between" data-aos="fade-up" data-aos-delay="300">
						<div class="colmn-box">
							<div class="text-box">
								<h6>Full stack developer: Tiago E.</h6>
								<p>6+ years experience</p>
								<ul>
									<li>Javascript</li>
									<li>React</li>
									<li>Java8</li>
									<li>Ruby on Rails</li>
									<li>Git</li>
									<li>Kotlin</li>
								</ul>
							</div>
							<a href="javascript:void(0)" data-id="Tiago E" class="btn-outline popup-btn">Contact</a>
						</div>
						<div class="colmn-box">
							<div class="text-box">
								<h6>Senior data scientist: Vamsi E.</h6>
								<p>9+ years experience</p>
								<ul>
									<li>Python</li>
									<li>R</li>
									<li>Java</li>
									<li>Scala</li>
									<li>Shell Scripting</li>
									<li>MySQL</li>
									<li>Hadoop</li>
									<li>Hive</li>
									<li>HBase</li>
									<li>Spark</li>
								</ul>
							</div>
							<a href="javascript:void(0)" data-id="Vamsi E" class="btn-outline popup-btn">Contact</a>
						</div>
						<div class="colmn-box">
							<div class="text-box">
								<h6>Senior front-end engineer: Mayowa F.</h6>
								<p>4+ years experience</p>
								<ul>
									<li>Javascript</li>
									<li>React</li>
									<li>Redux</li>
									<li>Angular</li>
									<li>Python</li>
									<li>Django</li>
									<li>NodeJS</li>
								</ul>
							</div>
							<a href="javascript:void(0)" data-id="Mayowa F" class="btn-outline popup-btn">Contact</a>
						</div>
						<div class="colmn-box">
							<div class="text-box">
								<h6>Team technical lead: Wesley C.</h6>
								<p>13+ years experience</p>
								<ul>
									<li>Azure</li>
									<li>Docker</li>
									<li>Angular 6</li>
									<li>Typescript</li>
									<li>Micro services</li>
									<li>.Net core 2</li>
									<li>RxJs</li>
									<li>HTML 5</li>
									<li>CSS</li>
									<li>Bootstrap</li>
									<li>Rest</li>
									<li>Oauth2</li>
									<li>C#</li>
									<li>CSS</li>
									<li>PHP</li>
								</ul>
							</div>
							<a href="javascript:void(0)" data-id="Wesley C" class="btn-outline popup-btn">Contact</a>
						</div>
						<div class="colmn-box">
							<div class="text-box">
								<h6>Data scientist: Ilya S.</h6>
								<p>4+ years experience</p>
								<ul>
									<li>Java</li>
									<li>Tensorflow</li>
									<li>Scala</li>
									<li>Hadoop</li>
									<li>Samza</li>
									<li>Spark</li>
									<li>Zeppelin</li>
									<li>Python</li>
									<li>PyTorch</li>
									<li>Mesos/Marathon</li>
									<li>Nginx</li>
									<li>Consul/Eureka</li>
								</ul>
							</div>
							<a href="javascript:void(0)" data-id="Ilya S" class="btn-outline popup-btn">Contact</a>
						</div>
						<div class="colmn-box">
							<div class="text-box">
								<h6>Back-end developer: Nilay A.</h6>
								<p>13+ years experience</p>
								<ul>
									<li>Ruby</li>
									<li>Ruby on Rails</li>
									<li>PHP</li>
									<li>Laravel</li>
									<li>Javascript</li>
									<li>Bootstrap</li>
									<li>React </li>
								</ul>
							</div>
							<a href="javascript:void(0)" data-id="Nilay A" class="btn-outline popup-btn">Contact</a>
						</div>
					</div>
				<a href="javascript:void(0)" class="btn popup-btn">View all</a>
				</div>
			</div>
			<div class="video-section text-center section-lg section-lg">
				<div class="container-sm">
					<h2 data-aos="fade-up">Candidate Success Story: Hafsa</h2>
					<div class="video-responsive"><iframe width="560" height="315" src="https://www.youtube.com/embed/bMjPgMvRJZY" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe></div>
				</div>
			</div>
			<div class="testimonials-section text-center gray-bg section-lg">
				<div class="container">
					<h2 data-aos="fade-up">Client Testimonials</h2>
					<div class="testimonial-slider" data-aos="fade-up" data-aos-delay="200">
						<div>
							<div class="slider-box">
								<figure>
									<img src="app/images/testimonial-img-1.png">
								</figure>
								<h6>Adam Rabbie</h6>
								<span>CEO, Big Terminal</span>
								<p>Yousuf and the Global Skills Hub team were able to gather our hiring needs quickly.
									Our team is a tight knit group of deeply committed engineers, so our threshold for
									experience and talent was equally high. Somehow, the Global Skills Hub team was able
									to find, evaluate and introduce Abdullah within one week. After a couple calls, code
									review and discussion, we were thrilled to bring Abdullah on. Our experience working
									with him has been exceptional. We look forward to partnering with Global Skills Hub
									on future roles.</p>
							</div>
						</div>
						<div>
							<div class="slider-box">
								<figure>
									<img src="app/images/Valerie.png">
								</figure>
								<h6>Valerie Rother</h6>
								<span>Director, People and Culture, Wave</span>
								<p>The team at Global Skills Hub was incredibly attentive to our needs and helped us identify an incredibly talented engineer in Nigeria.  She's a perfect fit for Wave in every way and we look forward to all the great things she will accomplish here.  I was highly skeptical that the immigration process would be as easy as advertised but true to their word, the GSH team removed all the fear from the process and our experience was seamless.</p>
							</div>
						</div>
						<div>
							<div class="slider-box">
								<figure>
									<img src="app/images/testimonial-img-3.jpeg">
								</figure>
								<h6>Jarett Macleod</h6>
								<span>People and Culture Specialist, Opus One Solutions</span>
								<p>Working with Global Skills Hub has demystified the process of global hiring. With
									their help, we feel prepared to tackle the complicated immigration process and scale
									our team with a global workforce. They really got to the heart of what makes our
									culture tick and helped us find a great fit.</p>
							</div>
						</div>
						<div>
							<div class="slider-box">
								<figure>
									<img src="app/images/testimonial-img-4.jpeg">
								</figure>
								<h6>Vitaliy Lim</h6>
								<span>Founder & CTO, Feroot</span>
								<p>What I love about Global Skills Hub is that I don't have to worry about not being
									able to find and hire talent anymore. It provides end-to-end hiring platform to find
									and acquire talent all around the world. Customer support is instrumental in
									recruitment and our experience working with Global Skills Hub is a great testament
									to it.</p>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="strip-section text-center bg-control color-white"
				style="background-image: url(app/images/strip-bg.png)">
				<h3>Ready to start finding talent?</h3>
				<a href="javascript:void(0)" class="btn popup-btn">Find talent</a>
			</div>
			<div class="map-section section-lg text-center" id="contactSection">
				<h2>Contact Us</h2>
				<div class="map-wrapper">
					<div class="container">
						<div class="detail-box">
							<h4>Global Skills Hub 425 Adelaide St. W, Toronto, ON M6J 3S8</h4>
							<ul>
								<li><a href="tel:905-367-5207">Map <span>905-367-5207</span></a></li>
								<li><a href="mailto:hello@globalskills.io">hello@globalskills.io</a></li>
							</ul>
						</div>
						<div class="map-box">
							<iframe
								src="https://www.google.com/maps/embed?pb=!1m16!1m12!1m3!1d2887.099489539313!2d-79.39992928416626!3d43.646098379121526!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!2m1!1sGlobal+Skills+Hub+425+Adelaide+St.+W%2C+Toronto%2C+ON+M6J+3S8!5e0!3m2!1sen!2sin!4v1551735293027"
								frameborder="0" style="border:0" allowfullscreen></iframe>
						</div>
					</div>
				</div>
				<ul class="btn-list">
					<li><a href="javascript:void(0)" class="btn popup-btn">Request Details</a></li>
					<li><a href="javascript:void(0)" class="btn popup-btn">Schedule Call</a></li>
				</ul>
			</div>
			<div class="client-section section-lg section-lg">
				<div class="container">
					<ul>
						<li><img src="app/images/client-log-1.png"></li>
						<li><img src="app/images/kik-grey.png"></li>
						<li><img src="app/images/client-log-3.png"></li>
						<li><img src="app/images/client-log-4.png"></li>
						<li><img src="app/images/client-log-5.png"></li>
					</ul>
				</div>
			</div>
		</main>
		<div class="popup-section">
			<div class="popup-box">
				<span class="btn-close"><i class="fa fa-times" aria-hidden="true"></i></span>
				<div class="popup-form">
					<h3>How can we help you?</h3>
					<p>Send us a message for a prompt reply.</p>
					<div class="form-group">
					<form action="https://formspree.io/xwnrpapm" method="POST">
  <label>
    Your email:
    <input type="text" name="_replyto" class="form-control" placeholder="Work e-mail (Required)">
  </label>
  <label>
    Your message:
    <textarea name="message" rows="5" class="form-control" id="message" placeholder="Message"></textarea>
  </label>
<input type="hidden" name="_subject" value="Inquiry from Global Skills Hub website" />
<input type="hidden" name="_next" value="http://globalskills.io/thanks.html"/>

  <!-- your other form fields go here -->

  <button type="submit" class="btn">Send</button>
</form>
			</div>
				<div class="popup-footer">
					<h5>Or, schedule a call:</h5>
					<div class="calendly-inline-widget" data-url="https://calendly.com/yousufk/20min"
						style="min-width:250px;height:600px;"></div>
					<script type="text/javascript" src="https://assets.calendly.com/assets/external/widget.js"></script>
				</div>
			</div>
		</div>
		</div>
		<footer>
			<div class="container">
				<div class="flex">
					<div class="colmn-box colmn-box-lg">
						<h6>Stay in touch</h6>
						<p>Join our community newletter and receive monthly features about jobs in Canada,
							candidate success stories, and more.</p>
						<form
							action="https://globalskills.us19.list-manage.com/subscribe/post?u=85c478fcea6a894d91f2be5db&amp;id=78d4541e04"
							method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form"
							class="validate" target="_blank" novalidate>
							<div class="form-box">
								<input type="text" name="EMAIL" class="required email" id="mce-EMAIL"
									placeholder="Enter your e-mail">
								<button type="submit" name="subscribe" id="mc-embedded-subscribe"><i
										class="fa fa-telegram" aria-hidden="true"></i></button>
							</div>
							<div id="mce-responses" class="clear">
								<div class="response" id="mce-error-response" style="display:none"></div>
								<div class="response" id="mce-success-response" style="display:none"></div>
							</div>
							<!-- real people should not fill this in and expect good things - do not remove this or risk form bot signups-->
							<div style="position: absolute; left: -5000px;" aria-hidden="true"><input type="text"
									name="b_85c478fcea6a894d91f2be5db_78d4541e04" tabindex="-1" value=""></div>
						</form>
					</div>
					<div class="colmn-box">
						<h6>Social</h6>
						<ul class="social-icon">
							<li><a target="_blank" href="https://www.linkedin.com/company/get-global-skills"><i class="fa fa-linkedin" aria-hidden="true"></i></a></li>
							<li><a target="_blank" href="#"><i class="fa fa-instagram" aria-hidden="true"></i></a></li>
							<li><a target="_blank" href="https://www.facebook.com/globalskillshub/"><i class="fa fa-facebook" aria-hidden="true"></i></a></li>
							<li><a target="_blank" href="https://twitter.com/globalskillshub?lang=en"><i class="fa fa-twitter" aria-hidden="true"></i></a></li>
						</ul>
					</div>
					<div class="colmn-box">
						<h6>Navigation</h6>
						<ul>
							<ul>
								<li><a href="faq.html">FAQ</a></li>
								<li><a href="/blog">Blog</a></li>
								<li><a href="about.html">About</a></li>
								<li><a href="#contactSection">Contact</a></li>
							</ul>
						</ul>
					</div>
				</div>
			</div>
			<div class="secondery-footer">
				<p>Global Skills Hub 425 Adelaide St. W, Toronto, ON M6J 3S8 <a href="tel:905-367-5207"><span>
							905-367-5207 </span></a><a href="mailto:hello@globalskills.io">hello@globalskills.io</a></p>
				<span>© 2019 Global Skills Hub</span>
			</div>
		</footer>
		
		<script type="text/javascript" src="https://assets.calendly.com/assets/external/widget.js"></script>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
		<script type="text/javascript"
			src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
		<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
		<script src="js/main.js"></script>
		<script>
			AOS.init({
				duration: 1000,
				once: true,
				disable: 'mobile',
			});
		</script>
		<script type='text/javascript' src='//s3.amazonaws.com/downloads.mailchimp.com/js/mc-validate.js'></script>
		<script
			type='text/javascript'>(function ($) { window.fnames = new Array(); window.ftypes = new Array(); fnames[0] = 'EMAIL'; ftypes[0] = 'email'; fnames[1] = 'FNAME'; ftypes[1] = 'text'; fnames[2] = 'LNAME'; ftypes[2] = 'text'; fnames[3] = 'ADDRESS'; ftypes[3] = 'address'; fnames[4] = 'PHONE'; ftypes[4] = 'phone'; }(jQuery)); var $mcj = jQuery.noConflict(true);</script>
	</body>

</html>
<?php
}else{
	


?>

<!DOCTYPE html>
<html lang="en">

	<head>

		<!-- Facebook Pixel Code -->
<script>
  !function(f,b,e,v,n,t,s)
  {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
  n.callMethod.apply(n,arguments):n.queue.push(arguments)};
  if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
  n.queue=[];t=b.createElement(e);t.async=!0;
  t.src=v;s=b.getElementsByTagName(e)[0];
  s.parentNode.insertBefore(t,s)}(window, document,'script',
  'https://connect.facebook.net/en_US/fbevents.js');
  fbq('init', '859990684360389');
  fbq('track', 'PageView');
</script>
<noscript><img height="1" width="1" style="display:none"
  src="https://www.facebook.com/tr?id=859990684360389&ev=PageView&noscript=1"
/></noscript>
<!-- End Facebook Pixel Code -->
			
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-134031783-3"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-134031783-3');
</script>


			<!-- COMMON TAGS -->
<meta charset="utf-8">
<!-- Search Engine -->
<meta name="description" content="Making global hiring easy with our pool of skilled, vetted talent, ready to relocate to Canada.">
<meta name="image" content="http://www.globalskills.io/app/images/shareimg.jpg">
<!-- Schema.org for Google -->
<meta itemprop="name" content="Global Skills Hub">
<meta itemprop="description" content="Making global hiring easy with our pool of skilled, vetted talent, ready to relocate to Canada.">
<meta itemprop="image" content="http://www.globalskills.io/app/images/shareimg.jpg">
<!-- Open Graph general (Facebook, Pinterest & Google+) -->
<meta name="og:title" content="Global Skills Hub">
<meta name="og:description" content="Making global hiring easy with our pool of skilled, vetted talent, ready to relocate to Canada.">
<meta name="og:image" content="http://www.globalskills.io/app/images/shareimg.jpg">
<meta name="og:url" content="http://www.globalskills.io">
<meta name="og:site_name" content="Global Skills Hub">
<meta name="og:locale" content="en_CA">
<meta name="og:type" content="website">
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta http-equiv="X-UA-Compatible" content="ie=edge">
		<link rel="icon" href="app/images/icon.png" type="image/x-icon" />
		<title>Home | Global Skills Hub</title>
		<link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
		<link rel="stylesheet" type="text/css"
			href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css" />
		<link rel="stylesheet" href="app/css/style.min.css">
	</head>

	<body>
		<header>
			<div class="container">
				<div class="flex items-align-center space-between">
					<div class="navbar-brand">
						<a href="/">
							<img src="app/images/logo.png">
						</a>
						<div class="toggle-btn">
							<span class="menu-item"></span>
							<span class="menu-item"></span>
							<span class="menu-item"></span>
						</div>
					</div>
					<div class="navigation">
						<ul>
							<li><a href="faq.html">FAQ</a></li>
							<li><a href="/blog">Blog</a></li>
							<li><a href="about.html">About</a></li>
							<li><a class="scroll-btn" href="#contactSection">Contact</a></li>
						</ul>
						<ul class="social-icon">
							<li><a target="_blank" href="#"><i class="fa fa-linkedin" aria-hidden="true"></i></a></li>
							<li><a target="_blank" href="#"><i class="fa fa-instagram" aria-hidden="true"></i></a></li>
							<li><a target="_blank" href="#"><i class="fa fa-facebook" aria-hidden="true"></i></a></li>
							<li><a target="_blank" href="#"><i class="fa fa-twitter" aria-hidden="true"></i></a></li>
						</ul>
					</div>
				</div>
			</div>
		</header>
		<div class="banner-section" style="background-image: url(app/images/banner-img.jpg)">
			<div class="container">
				<div class="banner-text color-white banner-text-lg">
					<h1 data-aos="fade-up"> Your path to a thriving life and career in Canada begins here</h1>
					<p data-aos="fade-up" data-aos-delay="200">Find a tech job in Canada and be approved in two weeks.
						Salaries ranging from $80,000 to $150,000.</p>
					<ul data-aos="fade-up" data-aos-delay="400">
						<li><a class="typeform-share button" href="https://globalskillshub.typeform.com/to/sJyat3" data-mode="popup" style="display:inline-block;text-decoration:none;background-color:#16C944;color:white;cursor:pointer;font-family:Helvetica,Arial,sans-serif;font-size:20px;line-height:50px;text-align:center;margin:0;height:50px;padding:0px 33px;border-radius:25px;max-width:100%;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;font-weight:bold;-webkit-font-smoothing:antialiased;-moz-osx-font-smoothing:grayscale;" target="_blank">Get Started </a> <script> (function() { var qs,js,q,s,d=document, gi=d.getElementById, ce=d.createElement, gt=d.getElementsByTagName, id="typef_orm_share", b="https://embed.typeform.com/"; if(!gi.call(d,id)){ js=ce.call(d,"script"); js.id=id; js.src=b+"embed.js"; q=gt.call(d,"script")[0]; q.parentNode.insertBefore(js,q) } })() </script></li>
						<li><a href="employer.html">I'm an employer</a></li>
					</ul>
				</div>
				<div class="trusted-section text-center">
					<p>Trusted by:</p>
					<ul>
						<li><img src="app/images/client-1.png"></li>
						<li><img src="app/images/kik-logo.png"></li>
						<li><img src="app/images/client-3.png"></li>
						<li><img src="app/images/client-4.png"></li>
						<li><img src="app/images/client-5.png"></li>
					</ul>
				</div>
			</div>
		</div>
		<main>
			<div class="work-section text-center section-lg">
				<div class="container">
					<h2 data-aos="fade-up">What We Do</h2>
					<div class="flex space-between">
						<div class="colmn-box" data-aos="fade-up" data-aos-delay="200">
							<figure>
								<img src="app/images/work-icon-5.png">
							</figure>
							<h6>Find you a high-paying tech job in Canada</h6>
							<p>Salaries range from $80,000 to $150,000</p>
						</div>
						<div class="colmn-box" data-aos="fade-up" data-aos-delay="400">
							<figure>
								<img src="app/images/work-icon-6.png">
							</figure>
							<h6>Handle the Express Immigration Process</h6>
							<p>Enjoy approval times of two weeks, you don't pay anything.</p>
						</div>
						<div class="colmn-box" data-aos="fade-up" data-aos-delay="400">
							<figure>
								<img src="app/images/work-icon-7.png">
							</figure>
							<h6>Help your spouse work here right away</h6>
							<p>Open work permits for spouses and study permits for dependents are also processed in two
								weeks when applicable.</p>
						</div>
					</div>
				</div>
			</div>
			<div class="strip-section text-center bg-control color-white"
				style="background-image: url(app/images/strip-bg.png)">
				<h3>Ready to advance your career?</h3>
				<a class="typeform-share button" href="https://globalskillshub.typeform.com/to/sJyat3" data-mode="popup" style="display:inline-block;text-decoration:none;background-color:#16C944;color:white;cursor:pointer;font-family:Helvetica,Arial,sans-serif;font-size:20px;line-height:50px;text-align:center;margin:0;height:50px;padding:0px 33px;border-radius:25px;max-width:100%;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;font-weight:bold;-webkit-font-smoothing:antialiased;-moz-osx-font-smoothing:grayscale;" target="_blank">Get Started </a> <script> (function() { var qs,js,q,s,d=document, gi=d.getElementById, ce=d.createElement, gt=d.getElementsByTagName, id="typef_orm_share", b="https://embed.typeform.com/"; if(!gi.call(d,id)){ js=ce.call(d,"script"); js.id=id; js.src=b+"embed.js"; q=gt.call(d,"script")[0]; q.parentNode.insertBefore(js,q) } })() </script>
			</div>
			<div class="video-section text-center section-lg section-lg">
				<div class="container-sm">
					<h2 data-aos="fade-up">Candidate Success Story: Hafsa</h2>
					<div class="video-responsive"><iframe width="560" height="315" src="https://www.youtube.com/embed/KX4K-38hPgg" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe></div>
				</div>
			</div>
			<div class="work-section text-center section-lg pt-0">
				<div class="container">
					<h2 data-aos="fade-up">How It Works</h2>
					<div class="flex space-between">
						<div class="colmn-box" data-aos="fade-up" data-aos-delay="200">
							<figure>
								<img src="app/images/work-icon-1.png">
							</figure>
							<h6>Fill out your information</h6>
							<p>Find out if you qualify by submitting some basic information (name, email, years of
								experience, previous experience) through our sign up form on the website.</p>
						</div>
						<div class="colmn-box" data-aos="fade-up" data-aos-delay="300">
							<figure>
								<img src="app/images/work-icon-2.png">
							</figure>
							<h6>Connect with our experts</h6>
							<p>Qualified applicants will be contacted by an ambassador to find out more about you and
								optimize your resume. You will complete a technical evaluation and a personality test.
							</p>
						</div>
						<div class="colmn-box" data-aos="fade-up" data-aos-delay="400">
							<figure>
								<img src="app/images/work-icon-3.png">
							</figure>
							<h6>Your profile is submitted</h6>
							<p>Your dedicated Talent Ambassador will submit your completed profile to our hiring
								companies and facilitate the interview scheduling. Our team works with you to prepare
								for interviews.</p>
						</div>
						<div class="colmn-box" data-aos="fade-up" data-aos-delay="500">
							<figure>
								<img src="app/images/work-icon-4.png">
							</figure>
							<h6>Get hired and relocate</h6>
							<p>Once you receive an offer, we will arrange all the details for you and your family’s
								applications (acceptance is approximately 2-3 weeks) and for relocation. We will greet
								you upon arrival and welcome you to Canada.</p>
						</div>
					</div>
				</div>
			</div>
			<div class="strip-section text-center bg-control color-white"
				style="background-image: url(app/images/strip-bg.png)">
				<h3>Ready to advance your career?</h3>
				<a class="typeform-share button" href="https://globalskillshub.typeform.com/to/sJyat3" data-mode="popup" style="display:inline-block;text-decoration:none;background-color:#16C944;color:white;cursor:pointer;font-family:Helvetica,Arial,sans-serif;font-size:20px;line-height:50px;text-align:center;margin:0;height:50px;padding:0px 33px;border-radius:25px;max-width:100%;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;font-weight:bold;-webkit-font-smoothing:antialiased;-moz-osx-font-smoothing:grayscale;" target="_blank">Get Started </a> <script> (function() { var qs,js,q,s,d=document, gi=d.getElementById, ce=d.createElement, gt=d.getElementsByTagName, id="typef_orm_share", b="https://embed.typeform.com/"; if(!gi.call(d,id)){ js=ce.call(d,"script"); js.id=id; js.src=b+"embed.js"; q=gt.call(d,"script")[0]; q.parentNode.insertBefore(js,q) } })() </script>
			</div>
			<div class="testimonials-section text-center gray-bg section-lg">
				<div class="container">
					<h2 data-aos="fade-up">Candidate Testimonials</h2>
					<div class="testimonial-slider" data-aos="fade-up" data-aos-delay="200">
						<div>
							<div class="slider-box">
								<figure>
									<img src="app/images/testimonial-img-sec-1.png">
								</figure>
								<h6>Abdullah</h6>
								<span>Senior Machine Learning Engineer, Big Terminal</span>
								<p>Global Skills Hub has fulfilled my dream of getting hired in Canada in a fast and
									reliable way. The ‘try-before-you-hire' model enabled me to showcase my technical
									abilities on a live project. After a month working remotely for a Canadian company,
									I was offered a full-time job and was fully supported by the Global Skills Hub team
									in processing the documentation for the visa and work permit and settling in
									Toronto!</p>
							</div>
						</div>
						<div>
							<div class="slider-box">
								<figure>
									<img src="app/images/testimonial-img-sec-2.png">
								</figure>
								<h6>Hafsa</h6>
								<span>Full Stack Engineer, TWG</span>
								<p>When the Global Skills e-mail came in, they connected me with a company and I got an
									interview. 45 documents were included to get my work permit. When Global Skills Hub
									jumped in, they were very professional and knowledgeable about all the procedures.
									When I finally made it, it was unbelievable.</p>
							</div>
						</div>
						<div>
							<div class="slider-box">
								<figure>
									<img src="app/images/testimonial-img-sec-3.png">
								</figure>
								<h6>Khaja</h6>
								<span>Senior Full Stack Developer, Ample</span>
								<p>I got in touch with Yousuf and had a quick chat with him about the opportunities in
									Canada. I haven't seen any one else work so persistently for me, this was a huge
									surprise for me. A few other things which were surprising were that GSH never asked
									me any money. And, once I cleared an interview the rest of the process was
									methodically laid out and was very easy with GSH's guidance. Keep doing the great
									work that you guys are doing.</p>
							</div>
						</div>
						<div>
							<div class="slider-box">
								<figure>
									<img src="app/images/testimonial-img-sec-4.png">
								</figure>
								<h6>Maryam</h6>
								<span>Software Engineer, Wave Financial</span>
								<p>What surprised me the most about Global Skills Hub was how everyone I was fortunate
									to come in contact with were so welcoming and open to invest their time in helping
									to make sure that I was in safe hands and well-prepared before going forth to
									interview with different companies. And when things didn't go well with some of
									those companies, how everyone was so invested in making sure I didn't doubt myself
									but instead to believe that a better opportunity would arise, which it eventually
									did!</p>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="map-section section-lg text-center" id="contactSection">
				<h2>Contact Us</h2>
				<div class="map-wrapper">
					<div class="container">
						<div class="detail-box">
							<h4>Global Skills Hub 425 Adelaide St. W, Toronto, ON M6J 3S8</h4>
							<ul>
								<li><a class="typeform-share link" href="https://globalskillshub.typeform.com/to/sJyat3" data-mode="popup" style="color:#267DDD;text-decoration:underline;font-size:20px;" target="_blank">Get Started</a> <script> (function() { var qs,js,q,s,d=document, gi=d.getElementById, ce=d.createElement, gt=d.getElementsByTagName, id="typef_orm_share", b="https://embed.typeform.com/"; if(!gi.call(d,id)){ js=ce.call(d,"script"); js.id=id; js.src=b+"embed.js"; q=gt.call(d,"script")[0]; q.parentNode.insertBefore(js,q) } })() </script></li>
							</ul>
						</div>
						<div class="map-box">
							<iframe
								src="https://www.google.com/maps/embed?pb=!1m16!1m12!1m3!1d2887.099489539313!2d-79.39992928416626!3d43.646098379121526!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!2m1!1sGlobal+Skills+Hub+425+Adelaide+St.+W%2C+Toronto%2C+ON+M6J+3S8!5e0!3m2!1sen!2sin!4v1551735293027"
								frameborder="0" style="border:0" allowfullscreen></iframe>
						</div>
					</div>
				</div>
				<ul class="btn-list">
					<li><a class="typeform-share button" href="https://globalskillshub.typeform.com/to/sJyat3" data-mode="popup" style="display:inline-block;text-decoration:none;background-color:#16C944;color:white;cursor:pointer;font-family:Helvetica,Arial,sans-serif;font-size:20px;line-height:50px;text-align:center;margin:0;height:50px;padding:0px 33px;border-radius:25px;max-width:100%;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;font-weight:bold;-webkit-font-smoothing:antialiased;-moz-osx-font-smoothing:grayscale;" target="_blank">Get Started </a> <script> (function() { var qs,js,q,s,d=document, gi=d.getElementById, ce=d.createElement, gt=d.getElementsByTagName, id="typef_orm_share", b="https://embed.typeform.com/"; if(!gi.call(d,id)){ js=ce.call(d,"script"); js.id=id; js.src=b+"embed.js"; q=gt.call(d,"script")[0]; q.parentNode.insertBefore(js,q) } })() </script></li>
				</ul>
			</div>
			<div class="client-section section-lg section-lg">
				<div class="container">
					<ul>
						<li><img src="app/images/client-log-1.png"></li>
						<li><img src="app/images/kik-grey.png"></li>
						<li><img src="app/images/client-log-3.png"></li>
						<li><img src="app/images/client-log-4.png"></li>
						<li><img src="app/images/client-log-5.png"></li>
					</ul>
				</div>
			</div>
		</main>
		<div class="popup-section">
			<div class="popup-box">
				<span class="btn-close"><i class="fa fa-times" aria-hidden="true"></i></span>
				<div class="popup-form">
					<h3>How can we help you?</h3>
					<p>Send us a message for a prompt reply.</p>
					<div class="form-group">
						<input type="text" class="form-control" placeholder="Work e-mail (Required)">
					</div>
					<div class="form-group">
						<textarea class="form-control" id="message" placeholder="Message" rows="5"></textarea>
					</div>
					<button type="button" class="btn">Send</button>
				</div>
				<div class="popup-footer">
					<h5>Or, schedule a call:</h5>
					<div class="calendly-inline-widget" data-url="https://calendly.com/yousufk/20min"
						style="min-width:250px;height:600px;"></div>
					<script type="text/javascript" src="https://assets.calendly.com/assets/external/widget.js"></script>
				</div>
			</div>
		</div>
		<footer>
			<div class="container">
				<div class="flex">
					<div class="colmn-box colmn-box-lg">
						<h6>Stay in touch</h6>
						<p>Join our community newsletter and receive monthly features about jobs in Canada,
							candidate success stories, and more.</p>
						<form
							action="https://globalskills.us19.list-manage.com/subscribe/post?u=85c478fcea6a894d91f2be5db&amp;id=78d4541e04"
							method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form"
							class="validate" target="_blank" novalidate>
							<div class="form-box">
								<input type="text" name="EMAIL" class="required email" id="mce-EMAIL"
									placeholder="Enter your e-mail">
								<button type="submit" name="subscribe" id="mc-embedded-subscribe"><i
										class="fa fa-telegram" aria-hidden="true"></i></button>
							</div>
							<div id="mce-responses" class="clear">
								<div class="response" id="mce-error-response" style="display:none"></div>
								<div class="response" id="mce-success-response" style="display:none"></div>
							</div>
							<!-- real people should not fill this in and expect good things - do not remove this or risk form bot signups-->
							<div style="position: absolute; left: -5000px;" aria-hidden="true"><input type="text"
									name="b_85c478fcea6a894d91f2be5db_78d4541e04" tabindex="-1" value=""></div>
						</form>
					</div>
					<div class="colmn-box">
						<h6>Social</h6>
						<ul class="social-icon">
							<li><a target="_blank" href="https://www.linkedin.com/company/get-global-skills"><i class="fa fa-linkedin" aria-hidden="true"></i></a></li>
							<li><a target="_blank" href="#"><i class="fa fa-instagram" aria-hidden="true"></i></a></li>
							<li><a target="_blank" href="https://www.facebook.com/globalskillshub/"><i class="fa fa-facebook" aria-hidden="true"></i></a></li>
							<li><a target="_blank" href="https://twitter.com/globalskillshub?lang=en"><i class="fa fa-twitter" aria-hidden="true"></i></a></li>
						</ul>
					</div>
					<div class="colmn-box">
						<h6>Navigation</h6>
												<ul>
							<ul>
								<li><a href="faq.html">FAQ</a></li>
								<li><a href="/blog">Blog</a></li>
								<li><a href="about.html">About</a></li>
								<li><a href="#contactSection">Contact</a></li>
							</ul>

					</div>
				</div>
			</div>
			<div class="secondery-footer">
				<p>Global Skills Hub 425 Adelaide St. W, Toronto, ON M6J 3S8 <a class="typeform-share link" href="https://globalskillshub.typeform.com/to/sJyat3" data-mode="popup" style="color:#267DDD;text-decoration:underline;font-size:16px;" target="_blank">Sign Up </a> <script> (function() { var qs,js,q,s,d=document, gi=d.getElementById, ce=d.createElement, gt=d.getElementsByTagName, id="typef_orm_share", b="https://embed.typeform.com/"; if(!gi.call(d,id)){ js=ce.call(d,"script"); js.id=id; js.src=b+"embed.js"; q=gt.call(d,"script")[0]; q.parentNode.insertBefore(js,q) } })() </script></p>
				<span>© 2019 Global Skills Hub</span>
			</div>
		</footer>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
		<script type="text/javascript"
			src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
		<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
		<script src="js/main.js"></script>
		<script>
			AOS.init({
				duration: 1000,
				once: true,
				disable: 'mobile',
			});
		</script>
		<script type='text/javascript' src='//s3.amazonaws.com/downloads.mailchimp.com/js/mc-validate.js'></script>
		<script
			type='text/javascript'>(function ($) { window.fnames = new Array(); window.ftypes = new Array(); fnames[0] = 'EMAIL'; ftypes[0] = 'email'; fnames[1] = 'FNAME'; ftypes[1] = 'text'; fnames[2] = 'LNAME'; ftypes[2] = 'text'; fnames[3] = 'ADDRESS'; ftypes[3] = 'address'; fnames[4] = 'PHONE'; ftypes[4] = 'phone'; }(jQuery)); var $mcj = jQuery.noConflict(true);</script>
	</body>

</html>
<?php
}
?>