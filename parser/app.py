"""Applicatinon entry point"""
from navigation.factories import (
        PageElementFactory,
        AdsListPageFactory,
        AdPageFactory)
from navigation import page
from parsing.scrappers import AdsListScrapper, AdScrapper
from common.driver import Driver
from data.records import AdLink
from config import Config


if __name__ == '__main__':
    config = Config()
    config.set_debug_mode(Driver())
    ads_list_scrapper = AdsListScrapper()
    ad_scrapper = AdScrapper()
    max_page_to_iterate = config.get_max_page_to_iterate()
    element_factory = PageElementFactory()
    ads_list_page_factory = AdsListPageFactory(element_factory)
    ad_page_factory = AdPageFactory(element_factory)
    page_iter = page.PageIterator(max_page_to_iterate, ads_list_page_factory)
    ad_link = AdLink()

    for page in page_iter:
        page.load()
        for link in ads_list_scrapper.get_ad_links(page):
            ad_link.create(link)

    for link in ad_link.all():
        page = ad_page_factory.create_page(link.url)
        page.load()
        print(ad_scrapper.scrap_info(page))
        break
