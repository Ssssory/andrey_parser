"""Applicatinon entry point"""
import json
from navigation.factories import (
        PageElementFactory,
        AdsListPageFactory,
        AdPageFactory)
from navigation import page
from parsing.scrappers import AdsListScrapper, AdScrapper
from common.driver import Driver
import data.dal as dal
from config import Config


if __name__ == '__main__':
    config = Config()
    dal.init_schema()
    ad_link_repo = dal.AdLinkRepo()
    config.set_debug_mode(Driver())
    ads_list_scrapper = AdsListScrapper()
    ad_scrapper = AdScrapper()
    max_page_to_iterate = config.get_max_page_to_iterate()
    element_factory = PageElementFactory()
    ads_list_page_factory = AdsListPageFactory(element_factory)
    ad_page_factory = AdPageFactory(element_factory)
    page_iter = page.PageIterator(max_page_to_iterate, ads_list_page_factory)
    urls = []

    for page in page_iter:
        page.load()
        for link in ads_list_scrapper.get_ad_links(page):
            urls.append((link,))

    ad_link_repo.bulk_insert(urls)

    for link in ad_link_repo.find_all():
        page = ad_page_factory.create_page(link.url)
        page.load()
        ad_info = ad_scrapper.scrap_info(page)
        ad_info_json = json.dumps(ad_info.__dict__, indent=4)
        #temporary for tests
        with open('temp_results.json', 'w') as f:
            f.write(ad_info_json)
        break
