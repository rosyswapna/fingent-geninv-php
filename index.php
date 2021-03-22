<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<meta http-equiv="X-UA-Compatible" content="ie=edge" />
		<title>Invoice</title>
		<link
			href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap"
			rel="stylesheet"
		/>
		<style>
			@media print {
				@page {
					size: A3;
				}
				.inv-body tfoot{
					display: none;
				}
				#generate-inv{
					display: none;
				}
			}
			ul {
				padding: 0;
				margin: 0 0 1rem 0;
				list-style: none;
			}
			body {
				font-family: "Inter", sans-serif;
				margin: 0;
			}
			table {
				width: 100%;
				border-collapse: collapse;
			}
			table,
			table th,
			table td {
				border: 1px solid silver;
			}
			table th,
			table td {
				text-align: right;
				padding: 8px;
			}
			h1,
			h4,
			p {
				margin: 0;
			}
			button{
				padding: 10px;
			}

			.container {
				padding: 20px 0;
				width: 1000px;
				max-width: 90%;
				margin: 0 auto;
			}

			.inv-title {
				padding: 10px;
				border: 1px solid silver;
				text-align: center;
				margin-bottom: 30px;
			}

			.inv-logo {
				width: 150px;
				display: block;
				margin: 0 auto;
				margin-bottom: 40px;
			}

			/* header */
			.inv-header {
				display: flex;
				margin-bottom: 20px;
			}
			.inv-header > :nth-child(1) {
				flex: 2;
			}
			.inv-header > :nth-child(2) {
				flex: 1;
			}
			.inv-header h2 {
				font-size: 20px;
				margin: 0 0 0.3rem 0;
			}
			.inv-header ul li {
				font-size: 15px;
				padding: 3px 0;
			}

			/* body */
			.inv-body table th,
			.inv-body table td {
				text-align: left;
			}
			.inv-body {
				margin-bottom: 30px;
			}

			/* footer */
			.inv-footer {
				display: flex;
				flex-direction: row;
			}
			.inv-footer > :nth-child(1) {
				flex: 2;
			}
			.inv-footer > :nth-child(2) {
				flex: 1;
			}
		</style>

		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
		<script>
		$(document).ready(function(){
			$("#generate-inv").hide();
			sub_total_without_tax = sub_total_with_tax = 0;
			discount_amount= 0;
			total_amount = 0;
			$("#add-item").click(function(){
				var tr = '';
				tr += '<tr>';
				tr += '<td>'+$("#item").val()+'</td>';
				tr += '<td>'+$("#quantity").val()+'</td>';
				tr += '<td>'+$("#price").val()+'</td>';
				tr += '<td>'+$("#tax").val()+'</td>';
				tr += '<td>'+$("#line-total").val()+'</td>';
				tr += '<td>';
				tr+= '</tr';
				$(".inv-body table tbody").append(tr);

				line_total = parseFloat($("#price").val())*parseFloat($("#quantity").val());
				tax = line_total * parseFloat($("#tax").val())/100;

				sub_total_without_tax += line_total;
				sub_total_with_tax += line_total+tax;
				$("#sub-total").val(sub_total_without_tax);
				$("#sub-total-tax").val(sub_total_with_tax);
				$("#total-amount").val(total_amount);
				setTotalAmount();
				clearValues();
				$("#generate-inv").show();
			});

			$("#price,#tax,#quantity").on('input',function(){
				if($("#price").val() != '' && $("#quantity").val() != ''){
					line_total = parseFloat($("#price").val())*parseFloat($("#quantity").val());
					tax = line_total * parseFloat($("#tax").val())/100;
					$("#line-total").val(line_total+tax);
					$("#add-item").prop('disabled',false);

				}else{
					$("#line-total").val("");
					$("#add-item").prop('disabled',true);
				}
			});

			$("#discount").on('blur',function(){
				setTotalAmount();
			});

			$("#generate-inv").click(function(){
				window.print();
			});
		});

		function clearValues()
		{
			$("#item").val("");
			$("#quantity").val("1");
			$("#price").val("");
			$("#tax").val("0");
			$("#line-total").val("");
			$("#add-item").prop('disabled',true);
		}
		function setTotalAmount()
		{
			if($("#discount").val() != ''){
				discount = parseFloat($("#discount").val());
			}else{
				discount = 0;
			}
			total_amount = sub_total_with_tax - discount;
			$("#total-amount").val(total_amount);
		}
		</script>
	</head>
	<body>
		<div class="container">
			<div class="inv-title">
				<h1>Invoice # 00001</h1>
			</div>

			<div class="inv-header">
				<div>
					<h2>Customer</h2>
					<ul>
						<li>Street Address, Zipcode</li>
						<li>Country</li>
						<li>Phone No | email</li>
					</ul>
				</div>
				<div>
					<table>
						<tr>
							<th>Issue Date</th>
							<td><?php echo date('d-m-Y')?></td>
						</tr>
						<tr>
							<th>Due Date</th>
							<td><?php echo date('d-m-Y',strtotime('+7 days'))?></td>
						</tr>
					</table>
				</div>
			</div>
			<div class="inv-body">
				<table>
					<thead>
						<th>Item</th>
						<th>Quantity</th>
						<th>Unit Price</th>
						<th>Tax</th>
						<th>Line Total</th>
						<th></th>
					</thead>
					<tbody></tbody>
					<tfoot>
						<tr>
							<td>
								<input type="text" id="item" />
							</td>
							<td><input type="number" id="quantity" min="1" /></td>
							<td><input type="number" id="price" /></td>
							<td>
								<select id="tax">
									<option value="0">0%</option>
									<option value="1">1%</option>
									<option value="5">5%</option>
									<option value="10">10%</option>
								</select>
							</td>
							<td><input disabled="" type="text" id="line-total"></td>
							<td><button disabled="" id="add-item">Add Item</button></td>
						</tr>
						
					</tfoot>
				</table>
			</div>
			<div class="inv-footer">
				<div><!-- required --></div>
				<div>
					<table>
						<tr>
							<th>Sub total without tax</th>
							<td><input disabled="" type="text" id="sub-total"></td>
						</tr>
						<tr>
							<th>Discount Amount</th>
							<td><input type="number" id="discount" value="0"></td>
						</tr>
						<tr>
							<th>Sub total with tax</th>
							<td><input disabled="" type="text" id="sub-total-tax"></td>
						</tr>
						<tr>
							<th>Total Amount</th>
							<td><input disabled="" type="text" id="total-amount"></td>
						</tr>
					</table>
				</div>
			</div>

			<center><button id="generate-inv">Generate Invoice</button></center>
		</div>
	</body>
</html>