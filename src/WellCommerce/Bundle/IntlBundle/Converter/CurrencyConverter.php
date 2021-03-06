<?php
/*
 * WellCommerce Open-Source E-Commerce Platform
 * 
 * This file is part of the WellCommerce package.
 *
 * (c) Adam Piotrowski <adam@wellcommerce.org>
 * 
 * For the full copyright and license information,
 * please view the LICENSE file that was distributed with this source code.
 */

namespace WellCommerce\Bundle\IntlBundle\Converter;

use Symfony\Component\HttpFoundation\RequestStack;
use WellCommerce\Bundle\IntlBundle\Entity\CurrencyRate;
use WellCommerce\Bundle\IntlBundle\Repository\CurrencyRateRepositoryInterface;

/**
 * Class CurrencyConverter
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class CurrencyConverter implements CurrencyConverterInterface
{
    /**
     * @var CurrencyRateRepositoryInterface
     */
    protected $currencyRateRepository;

    /**
     * @var array
     */
    protected $exchangeRates = [];

    /**
     * @var RequestStack
     */
    protected $requestStack;

    /**
     * @var string
     */
    protected $targetCurrency;

    /**
     * Constructor
     *
     * @param CurrencyRateRepositoryInterface $currencyRateRepository
     * @param RequestStack                    $requestStack
     */
    public function __construct(CurrencyRateRepositoryInterface $currencyRateRepository, RequestStack $requestStack)
    {
        $this->currencyRateRepository = $currencyRateRepository;
        $this->requestStack           = $requestStack;
    }

    /**
     * {@inheritdoc}
     */
    public function format($amount, $baseCurrency = null, $targetCurrency = null, $taxValue = 0)
    {
        $targetCurrency = $this->getTargetCurrency($targetCurrency);
        if (null === $baseCurrency) {
            $baseCurrency = $targetCurrency;
        }
        $amount    = $amount + $this->getTaxAmount($amount, $taxValue);
        $amount    = $this->convert($amount, $baseCurrency, $targetCurrency);
        $locale    = $this->requestStack->getMasterRequest()->getLocale();
        $formatter = new \NumberFormatter($locale, \NumberFormatter::CURRENCY);
        if (false === $result = $formatter->formatCurrency($amount, $targetCurrency)) {
            $e = sprintf('Cannot format price with amount "%s" and currency "%s"', $amount, $targetCurrency);
            throw new \InvalidArgumentException($e);
        }

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function convert($amount, $baseCurrency = null, $targetCurrency = null, $taxValue = 0)
    {
        $targetCurrency = $this->getTargetCurrency($targetCurrency);
        if (null === $baseCurrency) {
            $baseCurrency = $targetCurrency;
        }
        $amount = $amount + $this->getTaxAmount($amount, $taxValue);

        $this->loadExchangeRates($targetCurrency);

        $exchangeRate = $this->exchangeRates[$targetCurrency][$baseCurrency];

        return $amount * $exchangeRate;
    }

    /**
     * Returns target currency from passed argument or from session
     *
     * @param null|string $targetCurrency
     *
     * @return string
     */
    protected function getTargetCurrency($targetCurrency = null)
    {
        if (null === $targetCurrency) {
            $targetCurrency = $this->getCurrentSessionCurrency();
        }

        return $targetCurrency;
    }

    /**
     * Returns calculated tax amount
     *
     * @param float $amount
     * @param float $taxValue
     *
     * @return float
     */
    protected function getTaxAmount($amount, $taxValue)
    {
        return $amount * ($taxValue / 100);
    }

    /**
     * Returns currency code from session
     *
     * @return string
     */
    protected function getCurrentSessionCurrency()
    {
        $session = $this->requestStack->getMasterRequest()->getSession();

        return $session->get('_currency');
    }

    /**
     * Sets exchange rates for target currency
     *
     * @param string $targetCurrency
     */
    protected function loadExchangeRates($targetCurrency)
    {
        if (!isset($this->exchangeRates[$targetCurrency])) {
            $currencyRates = $this->currencyRateRepository->findBy(['currencyTo' => $targetCurrency]);
            if (count($currencyRates) === 0) {
                throw new \RuntimeException(sprintf('There are no exchange rates for "%s"', $targetCurrency));
            }
            foreach ($currencyRates as $rate) {
                $this->setExchangeRate($rate, $targetCurrency);
            }
        }
    }

    /**
     * Sets exchange rate for target and base currency pair
     *
     * @param CurrencyRate $rate
     * @param string       $targetCurrency
     */
    protected function setExchangeRate(CurrencyRate $rate, $targetCurrency)
    {
        $this->exchangeRates[$targetCurrency][$rate->getCurrencyFrom()] = $rate->getExchangeRate();
    }
}
