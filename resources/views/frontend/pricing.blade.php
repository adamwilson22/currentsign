@include('frontend.include.header')

   <section class="w-100 sec_pad">
           <div class="container ptb-100">
                    <div class="pricing-content">
                        <div class="row">
                        <div class="col-md-4 col-sm-4 col-xs-12">
                            <div class="table-list wow fadeInUp" data-wow-delay="0.3s" style="visibility: visible; animation-delay: 0.3s; animation-name: fadeInUp;">
                                <div class="top-price-inner">
                                    <h4>Basic</h4>
                                    <div class="rates">
                                        <span class="prices"><span class="dolar">$</span>15</span><span class="users">Per Month</span>
                                    </div>
                                </div>
                                <ol>
                                    <li class="check">10 documents per month</li>
                                    <li class="check">Core e-signing functionality</li>
                                    <li class="check">Email notifications and automated reminders</li>
                                    <li class="check">Signature tracking</li>
                                </ol>
                                <div class="">
                                    <a href="{{ url('/login') }}" class="btn btn-primary">Upgrade now</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-4 col-xs-12">
                            <div class="table-list wow fadeInUp" data-wow-delay="0.5s" style="visibility: visible; animation-delay: 0.5s; animation-name: fadeInUp;">
                                <div class="top-price-inner">
                                    <h4>Standard</h4>
                                    <div class="rates">
                                        <span class="prices"><span class="dolar">$</span>49</span><span class="users">Per Month</span>
                                    </div>
                                </div>
                                <ol>
                                    <li class="check">50 documents per month</li>
                                    <li class="check">All Basic features</li>
                                    <li class="check">Bulk-send capability (up to 20 recipients per batch)</li>
                                    <li class="check">Team collaboration (up to 3 users)</li>
                                </ol>
                                <div class="">
                                    <a href="{{ url('/login') }}" class="btn btn-primary">Upgrade now</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-4 col-xs-12">
                            <div class="table-list wow fadeInUp" data-wow-delay="0.7s" style="visibility: visible; animation-delay: 0.7s; animation-name: fadeInUp;">
                                <div class="top-price-inner">
                                    <h4>Premium</h4>
                                    <div class="rates">
                                        <span class="prices"><span class="dolar">$</span>99</span><span class="users">Per Month</span>
                                    </div>
                                </div>
                                <ol>
                                   <li class="check">Unlimited documents per month</li>
                                    <li class="check">All Standard features</li>
                                    <li class="check">Advanced templates & workflow automation</li>
                                    <li class="check">API access for custom integrations</li>
                                    <li class="check">Expanded team accounts (up to 10 users)</li>
                                    <li class="check">Priority support & dedicated account manager</li>
                                </ol>
                                <div class="">
                                    <a href="{{ url('/login') }}" class="btn btn-primary">Upgrade now</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    </div>
                </div>
        </section>


@include('frontend.include.footer')
</body>


</html>