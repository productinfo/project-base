<?php

namespace SS6\ShopBundle\TestsDb\Model\Order;

use SS6\ShopBundle\Component\Test\DatabaseTestCase;
use SS6\ShopBundle\Model\Payment\Payment;
use SS6\ShopBundle\Model\Payment\PaymentData;
use SS6\ShopBundle\Model\Payment\PaymentDomain;
use SS6\ShopBundle\Model\Pricing\Vat\Vat;
use SS6\ShopBundle\Model\Pricing\Vat\VatData;
use SS6\ShopBundle\Model\Transport\Transport;
use SS6\ShopBundle\Model\Transport\TransportData;
use SS6\ShopBundle\Model\Transport\TransportDomain;

class OrderTransportAndPaymentTest extends DatabaseTestCase {

	public function testVisibleTransport() {
		$em = $this->getEntityManager();

		$vat = new Vat(new VatData('vat', 21));
		$transport = new Transport(new TransportData('name', 0, $vat, 'description', false));
		$transportDomain = new TransportDomain($transport, 1);
		$payment = new Payment(new PaymentData('name', 0, $vat, 'description', false));
		$paymentDomain = new PaymentDomain($payment, 1);
		$payment->addTransport($transport);

		$em->persist($vat);
		$em->persist($transport);
		$em->flush();
		$em->persist($transportDomain);
		$em->persist($payment);
		$em->flush();
		$em->persist($paymentDomain);
		$em->flush();

		$transportEditFacade = $this->getContainer()->get('ss6.shop.transport.transport_edit_facade');
		/* @var $transportEditFacade \SS6\ShopBundle\Model\Transport\TransportEditFacade */
		$paymentEditFacade = $this->getContainer()->get('ss6.shop.payment.payment_edit_facade');
		/* @var $paymentEditFacade \SS6\ShopBundle\Model\Payment\PaymentEditFacade */

		$visiblePayments = $paymentEditFacade->getVisibleOnCurrentDomain();
		$visibleTransports = $transportEditFacade->getVisibleOnCurrentDomain($visiblePayments);

		$this->assertContains($transport, $visibleTransports);
	}

	public function testVisibleTransportHiddenTransport() {
		$em = $this->getEntityManager();

		$vat = new Vat(new VatData('vat', 21));
		$transport = new Transport(new TransportData('name', 0, $vat, 'description', true));
		$transportDomain = new TransportDomain($transport, 1);
		$payment = new Payment(new PaymentData('name', 0, $vat, 'description', false));
		$paymentDomain = new PaymentDomain($payment, 1);
		$payment->addTransport($transport);

		$em->persist($vat);
		$em->persist($transport);
		$em->flush();
		$em->persist($transportDomain);
		$em->persist($payment);
		$em->flush();
		$em->persist($paymentDomain);
		$em->flush();

		$transportEditFacade = $this->getContainer()->get('ss6.shop.transport.transport_edit_facade');
		/* @var $transportEditFacade \SS6\ShopBundle\Model\Transport\TransportEditFacade */
		$paymentEditFacade = $this->getContainer()->get('ss6.shop.payment.payment_edit_facade');
		/* @var $paymentEditFacade \SS6\ShopBundle\Model\Payment\PaymentEditFacade */

		$visiblePayments = $paymentEditFacade->getVisibleOnCurrentDomain();
		$visibleTransports = $transportEditFacade->getVisibleOnCurrentDomain($visiblePayments);

		$this->assertNotContains($transport, $visibleTransports);
	}

	public function testVisibleTransportHiddenPayment() {
		$em = $this->getEntityManager();

		$vat = new Vat(new VatData('vat', 21));
		$transport = new Transport(new TransportData('name', 0, $vat, 'description', false));
		$transportDomain = new TransportDomain($transport, 1);
		$payment = new Payment(new PaymentData('name', 0, $vat, 'description', true));
		$paymentDomain = new PaymentDomain($payment, 1);
		$payment->addTransport($transport);

		$em->persist($vat);
		$em->persist($transport);
		$em->flush();
		$em->persist($transportDomain);
		$em->persist($payment);
		$em->flush();
		$em->persist($paymentDomain);
		$em->flush();

		$transportEditFacade = $this->getContainer()->get('ss6.shop.transport.transport_edit_facade');
		/* @var $transportEditFacade \SS6\ShopBundle\Model\Transport\TransportEditFacade */
		$paymentEditFacade = $this->getContainer()->get('ss6.shop.payment.payment_edit_facade');
		/* @var $paymentEditFacade \SS6\ShopBundle\Model\Payment\PaymentEditFacade */

		$visiblePayments = $paymentEditFacade->getVisibleOnCurrentDomain();
		$visibleTransports = $transportEditFacade->getVisibleOnCurrentDomain($visiblePayments);

		$this->assertNotContains($transport, $visibleTransports);
	}

	public function testVisibleTransportNoPayment() {
		$em = $this->getEntityManager();

		$vat = new Vat(new VatData('vat', 21));
		$transport = new Transport(new TransportData('name', 0, $vat, 'description', false));
		$transportDomain = new TransportDomain($transport, 1);

		$em->persist($vat);
		$em->persist($transport);
		$em->flush();
		$em->persist($transportDomain);
		$em->flush();

		$transportEditFacade = $this->getContainer()->get('ss6.shop.transport.transport_edit_facade');
		/* @var $transportEditFacade \SS6\ShopBundle\Model\Transport\TransportEditFacade */
		$paymentEditFacade = $this->getContainer()->get('ss6.shop.payment.payment_edit_facade');
		/* @var $paymentEditFacade \SS6\ShopBundle\Model\Payment\PaymentEditFacade */

		$visiblePayments = $paymentEditFacade->getVisibleOnCurrentDomain();
		$visibleTransports = $transportEditFacade->getVisibleOnCurrentDomain($visiblePayments);

		$this->assertNotContains($transport, $visibleTransports);
	}

	public function testVisibleTransportOnDifferentDomain() {
		$em = $this->getEntityManager();

		$vat = new Vat(new VatData('vat', 21));
		$transport = new Transport(new TransportData('name', 0, $vat, 'description', false));
		$transportDomain = new TransportDomain($transport, 2);
		$payment = new Payment(new PaymentData('name', 0, $vat, 'description', false));
		$paymentDomain = new PaymentDomain($payment, 1);
		$payment->addTransport($transport);

		$em->persist($vat);
		$em->persist($transport);
		$em->flush();
		$em->persist($transportDomain);
		$em->persist($payment);
		$em->flush();
		$em->persist($paymentDomain);
		$em->flush();

		$transportEditFacade = $this->getContainer()->get('ss6.shop.transport.transport_edit_facade');
		/* @var $transportEditFacade \SS6\ShopBundle\Model\Transport\TransportEditFacade */
		$paymentEditFacade = $this->getContainer()->get('ss6.shop.payment.payment_edit_facade');
		/* @var $paymentEditFacade \SS6\ShopBundle\Model\Payment\PaymentEditFacade */

		$visiblePayments = $paymentEditFacade->getVisibleOnCurrentDomain();
		$visibleTransports = $transportEditFacade->getVisibleOnCurrentDomain($visiblePayments);

		$this->assertNotContains($transport, $visibleTransports);
	}

	public function testVisibleTransportPaymentOnDifferentDomain() {
		$em = $this->getEntityManager();

		$vat = new Vat(new VatData('vat', 21));
		$transport = new Transport(new TransportData('name', 0, $vat, 'description', false));
		$transportDomain = new TransportDomain($transport, 1);
		$payment = new Payment(new PaymentData('name', 0, $vat, 'description', false));
		$paymentDomain = new PaymentDomain($payment, 2);
		$payment->addTransport($transport);

		$em->persist($vat);
		$em->persist($transport);
		$em->flush();
		$em->persist($transportDomain);
		$em->persist($payment);
		$em->flush();
		$em->persist($paymentDomain);
		$em->flush();

		$transportEditFacade = $this->getContainer()->get('ss6.shop.transport.transport_edit_facade');
		/* @var $transportEditFacade \SS6\ShopBundle\Model\Transport\TransportEditFacade */
		$paymentEditFacade = $this->getContainer()->get('ss6.shop.payment.payment_edit_facade');
		/* @var $paymentEditFacade \SS6\ShopBundle\Model\Payment\PaymentEditFacade */

		$visiblePayments = $paymentEditFacade->getVisibleOnCurrentDomain();
		$visibleTransports = $transportEditFacade->getVisibleOnCurrentDomain($visiblePayments);

		$this->assertNotContains($transport, $visibleTransports);
	}

	public function testVisiblePayment() {
		$em = $this->getEntityManager();

		$vat = new Vat(new VatData('vat', 21));
		$transport = new Transport(new TransportData('name', 0, $vat, 'description', false));
		$transportDomain = new TransportDomain($transport, 1);
		$payment = new Payment(new PaymentData('name', 0, $vat, 'description', false));
		$paymentDomain = new PaymentDomain($payment, 1);
		$payment->addTransport($transport);

		$em->persist($vat);
		$em->persist($transport);
		$em->flush();
		$em->persist($transportDomain);
		$em->persist($payment);
		$em->flush();
		$em->persist($paymentDomain);
		$em->flush();

		$paymentEditFacade = $this->getContainer()->get('ss6.shop.payment.payment_edit_facade');
		/* @var $paymentEditFacade \SS6\ShopBundle\Model\Payment\PaymentEditFacade */

		$visiblePayments = $paymentEditFacade->getVisibleOnCurrentDomain();

		$this->assertContains($payment, $visiblePayments);
	}

	public function testVisiblePaymentHiddenTransport() {
		$em = $this->getEntityManager();

		$vat = new Vat(new VatData('vat', 21));
		$transport = new Transport(new TransportData('name', 0, $vat, 'description', true));
		$transportDomain = new TransportDomain($transport, 1);
		$payment = new Payment(new PaymentData('name', 0, $vat, 'description', false));
		$paymentDomain = new PaymentDomain($payment, 1);
		$payment->addTransport($transport);

		$em->persist($vat);
		$em->persist($transport);
		$em->flush();
		$em->persist($transportDomain);
		$em->persist($payment);
		$em->flush();
		$em->persist($paymentDomain);
		$em->flush();

		$paymentEditFacade = $this->getContainer()->get('ss6.shop.payment.payment_edit_facade');
		/* @var $paymentEditFacade \SS6\ShopBundle\Model\Payment\PaymentEditFacade */

		$visiblePayments = $paymentEditFacade->getVisibleOnCurrentDomain();

		$this->assertNotContains($payment, $visiblePayments);
	}

	public function testVisiblePaymentHiddenPayment() {
		$em = $this->getEntityManager();

		$vat = new Vat(new VatData('vat', 21));
		$transport = new Transport(new TransportData('name', 0, $vat, 'description', false));
		$transportDomain = new TransportDomain($transport, 1);
		$payment = new Payment(new PaymentData('name', 0, $vat, 'description', true));
		$paymentDomain = new PaymentDomain($payment, 1);
		$payment->addTransport($transport);

		$em->persist($vat);
		$em->persist($transport);
		$em->flush();
		$em->persist($transportDomain);
		$em->persist($payment);
		$em->flush();
		$em->persist($paymentDomain);
		$em->flush();

		$paymentEditFacade = $this->getContainer()->get('ss6.shop.payment.payment_edit_facade');
		/* @var $paymentEditFacade \SS6\ShopBundle\Model\Payment\PaymentEditFacade */

		$visiblePayments = $paymentEditFacade->getVisibleOnCurrentDomain();

		$this->assertNotContains($payment, $visiblePayments);
	}

	public function testVisiblePaymentNoTransport() {
		$em = $this->getEntityManager();

		$vat = new Vat(new VatData('vat', 21));
		$payment = new Payment(new PaymentData('name', 0, $vat, 'description', false));
		$paymentDomain = new PaymentDomain($payment, 1);

		$em->persist($vat);
		$em->flush();
		$em->persist($payment);
		$em->flush();
		$em->persist($paymentDomain);
		$em->flush();

		$paymentEditFacade = $this->getContainer()->get('ss6.shop.payment.payment_edit_facade');
		/* @var $paymentEditFacade \SS6\ShopBundle\Model\Payment\PaymentEditFacade */

		$visiblePayments = $paymentEditFacade->getVisibleOnCurrentDomain();

		$this->assertNotContains($payment, $visiblePayments);
	}

	public function testVisiblePaymentOnDifferentDomain() {
		$em = $this->getEntityManager();

		$vat = new Vat(new VatData('vat', 21));
		$transport = new Transport(new TransportData('name', 0, $vat, 'description', false));
		$transportDomain = new TransportDomain($transport, 1);
		$payment = new Payment(new PaymentData('name', 0, $vat, 'description', false));
		$paymentDomain = new PaymentDomain($payment, 2);
		$payment->addTransport($transport);

		$em->persist($vat);
		$em->persist($transport);
		$em->flush();
		$em->persist($transportDomain);
		$em->persist($payment);
		$em->flush();
		$em->persist($paymentDomain);
		$em->flush();

		$paymentEditFacade = $this->getContainer()->get('ss6.shop.payment.payment_edit_facade');
		/* @var $paymentEditFacade \SS6\ShopBundle\Model\Payment\PaymentEditFacade */

		$visiblePayments = $paymentEditFacade->getVisibleOnCurrentDomain();

		$this->assertNotContains($payment, $visiblePayments);
	}

	public function testVisiblePaymentTransportOnDifferentDomain() {
		$em = $this->getEntityManager();

		$vat = new Vat(new VatData('vat', 21));
		$transport = new Transport(new TransportData('name', 0, $vat, 'description', false));
		$transportDomain = new TransportDomain($transport, 2);
		$payment = new Payment(new PaymentData('name', 0, $vat, 'description', false));
		$paymentDomain = new PaymentDomain($payment, 1);
		$payment->addTransport($transport);

		$em->persist($vat);
		$em->persist($transport);
		$em->flush();
		$em->persist($transportDomain);
		$em->persist($payment);
		$em->flush();
		$em->persist($paymentDomain);
		$em->flush();

		$paymentEditFacade = $this->getContainer()->get('ss6.shop.payment.payment_edit_facade');
		/* @var $paymentEditFacade \SS6\ShopBundle\Model\Payment\PaymentEditFacade */

		$visiblePayments = $paymentEditFacade->getVisibleOnCurrentDomain();

		$this->assertNotContains($payment, $visiblePayments);
	}

}