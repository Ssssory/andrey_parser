"""Applicatinon entry point"""
import navigation.source as src
from navigation.element import PageElementFactory
from navigation import page
from parsing.scrappers import AdsListScrapper
from common.driver import Driver
from config import Config


if __name__ == '__main__':
    config = Config()
    config.set_debug_mode(Driver())
    ads_list_scrapper = AdsListScrapper(tag_name='img', css_class='object-cover')
    max_page_to_iterate = config.get_max_page_to_iterate()
    page_factory = page.RentsListPageFactory(
            PageElementFactory(),
            src.PopupXPaths,
            page.RentsListPage)
    page_iter = page.PageIterator(max_page_to_iterate, page_factory)
    links = None
    for page in page_iter:
        page.load()
        links = ads_list_scrapper.get_ad_links()

    print(links)
    #temporary for testing
    while True:
        pass
