<!DOCTYPE html>
<head>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<title>Search Results | Potomac</title>
<link rel="stylesheet" type="text/css" href="client/assets/css/bootstrap.min.css">
<script type="text/javascript" src="client/assets/js/jquery-1.11.1.min.js"></script>
<script type="text/javascript" src="client/assets/js/bootstrap-3.3.0.min.js"></script>
<script type="text/javascript" src="client/assets/js/results.js"></script>
<link rel="stylesheet" type="text/css" href="client/assets/css/results.css">

</head>
	<body>
	
		<nav class="navbar navbar-inverse">
			<div class="container-fluid">
				<!-- Brand and toggle get grouped for better mobile display -->
				<div class="navbar-header">
					<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<a class="navbar-brand" href="/">Potomac Law Group</a>
				</div>
				<!-- Collect the nav links, forms, and other content for toggling -->
				<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
					<ul class="nav navbar-nav navbar-right">
						<li><a href="/">Home</a></li>
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">More <span class="caret"></span></a>
							<ul class="dropdown-menu">
								<li><a href="login">Login</a></li>
								<li><a href="search">Search</a></li>
								<li role="separator" class="divider"></li>
								<li><a href="#">Documentation</a></li>
								<li><a href="#">About</a></li>
							</ul>
						</li>
					</ul>
				</div><!-- /.navbar-collapse -->
			</div><!-- /.container-fluid -->
		</nav>
		
		
				
			
<div class="container">
	<div class="row">

		<section class="content">
			<div class="jumbotron">
					<h1>Search Results</h1>
					<p align = "center">Results based upon your input keywords</p>				
				</div>
			</div>
			<div class="col-md-8 col-md-offset-2">
				<div class="panel panel-default">
					<div class="panel-body">
						<div class="pull-right">
							<div class="btn-group">
								<button type="button" class="btn btn-warning btn-filter" onclick="location.href = 'search';">Back To Search</button>
							</div>
						</div>
						<div class="table-container">
							<?php if(count($search_results) == 0) { ?>
							<h3>
								No search results found! Try changing the filters and keywords.
							</h3>
							<?php } ?>
							<table class="table table-filter">
								<tbody>
									<?php foreach($search_results as $result){ 

										if($result["file_type"] == "pdf")
										{
											$icon_location = "client/assets/images/pdfs.png";
											$class = "pdfs";
										}
										else
										{
											$icon_location = "client/assets/images/docs.png";
											$class = "docs";
										}

										?>
									<tr data-status="<?php echo $class; ?>">
										<td>			
									    </td>
										<td>
											<a href="<?php echo $result["file_location"] ?>" class="star">
												<i class="glyphicon glyphicon-download"></i>
											</a>
										</td>
										<td>
											<div class="media">
												<a href="#" class="pull-left">
													<img src="<?php echo $icon_location ?>" width = "80px" class="media-photo">
												</a>
												<div class="media-body">
													<span class="media-meta pull-right"><?php echo $result["date_added"]; ?></span>
													<h4 class="title">
														<?php echo $result["file_name"]; ?>
													</h4>
													<p class="summary"><?php echo strtoupper($result["file_type"]); ?></p>
												</div>
											</div>
										</td>
									</tr>
									<?php } ?>

								</tbody>
							</table>
						</div>
					</div>
				</div>
				
			</div>
			
		</section>
		
	</div>
</div>
</body>
</html>