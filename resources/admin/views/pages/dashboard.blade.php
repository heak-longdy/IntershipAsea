@extends('admin::shared.layout')
@section('layout')
    <style>

        .dashboard {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }

        .card {
            background-color: #fff;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .header h3 {
            font-size: 1.2rem;
            color: #333;
        }

        .header span {
            font-size: 0.9rem;
            color: #888;
        }

        .header select {
            margin-left: auto;
            font-size: 1rem;
        }

        .content h1 {
            font-size: 2.2rem;
            color: #333;
        }

        .stats p {
            font-size: 1rem;
            color: #666;
        }

        button {
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }

        .chart-placeholder {
            width: 100%;
            height: 150px;
            background-color: #eaeaea;
            border-radius: 10px;
            margin-top: 20px;
        }

        .salary-details,
        .product-list {
            margin-top: 15px;
        }

        .product {
            display: flex;
            justify-content: space-between;
        }

        .avatars img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 5px;
        }

        .product-image {
            width: 100%;
            height: auto;
            border-radius: 10px;
            margin-bottom: 10px;
        }
    </style>
    @include('admin::shared.header', ['header_name' => ''])
    <div class="dashboard-admin">

        <div class="dashboard">
            <!-- Revenue Updates -->
            <div class="card revenue-updates">
                <div class="header">
                    <h3>Revenue Updates</h3>
                    <span>Overview of Profit</span>
                    <select>
                        <option>March 2025</option>
                    </select>
                </div>
                <div class="content">
                    <h1>$63,489.50</h1>
                    <p>Total Earnings</p>
                    <div class="stats">
                        <p>Earnings this month: $48,820</p>
                        <p>Expense this month: $26,498</p>
                    </div>
                    <button>View Full Report</button>
                    <div class="chart-placeholder"></div>
                </div>
            </div>

            <!-- Yearly Breakup -->
            <div class="card yearly-breakup">
                <h3>Yearly Breakup</h3>
                <p>$36,358</p>
                <p>+9% last year</p>
                <div class="chart-placeholder"></div>
            </div>

            <!-- Monthly Earnings -->
            <div class="card monthly-earnings">
                <h3>Monthly Earnings</h3>
                <p>$6,820</p>
                <p>+9% last year</p>
                <div class="chart-placeholder"></div>
            </div>

            <!-- Employee Salary -->
            <div class="card employee-salary">
                <h3>Employee Salary</h3>
                <p>Every month</p>
                <div class="chart-placeholder"></div>
                <div class="salary-details">
                    <p>Salary: $36,358</p>
                    <p>Profit: $5,296</p>
                </div>
            </div>

            <!-- Customers and Projects -->
            <div class="card customers-projects">
                <div class="customers">
                    <h3>Customers</h3>
                    <p>36,358</p>
                    <p>+9%</p>
                    <div class="chart-placeholder"></div>
                </div>
                <div class="projects">
                    <h3>Projects</h3>
                    <p>78,298</p>
                    <p>+9%</p>
                    <div class="chart-placeholder"></div>
                </div>
            </div>

            <!-- Upcoming Event -->
            <div class="card upcoming-event">
                <h3>Super awesome, Vue coming soon!</h3>
                <p>22 March, 2025</p>
                <!-- Adding images from a URL -->
                <div class="avatars">
                    <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="Avatar 1">
                    <img src="https://randomuser.me/api/portraits/women/65.jpg" alt="Avatar 2">
                    <img src="https://randomuser.me/api/portraits/men/45.jpg" alt="Avatar 3">
                </div>
            </div>

            <!-- Best Selling Products -->
            <div class="card best-selling-products">
                <h3>Best Selling Products</h3>
                <p>Overview 2025</p>
                <!-- Adding an image as part of the product card -->
                <img src="https://example.com/image.png" alt="Product Image" class="product-image">
                <div class="product-list">
                    <div class="product">
                        <p>MaterialPro</p>
                        <span>55%</span>
                    </div>
                    <div class="product">
                        <p>Flexy Admin</p>
                        <span>20%</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
@section('script')
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script>
        $(document).ready(function() {
            $("#fromDate,#toDate").datepicker({
                changeYear: true,
                gotoCurrent: true,
                yearRange: "-1:+1",
                dateFormat: "yy-mm-dd",
            });
            @if (!request('from_date') && !request('to_date'))
                $("#toDate").datepicker('setDate', 'today');
                $("#toDate").datepicker("option", "minDate", new Date());
            @endif
            $("#fromDate").change(function() {
                let str = $(this).val();
                $("#toDate").datepicker("option", "minDate", new Date(str));
            });
        });
    </script>
@endsection
