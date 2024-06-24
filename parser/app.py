"""Applicatinon entry point"""
import navigation.source as src
from navigation import page
from navigation.element import PageElementFactory
from driver import Driver
from config import Config


if __name__ == '__main__':
    config = Config()
    config.set_debug_mode(Driver())
    max_page_to_iterate = config.get_max_page_to_iterate()
    page_factory = page.RentsListPageFactory(
            PageElementFactory(),
            src.PopupXPaths,
            page.RentsListPage)
    page_iter = page.PageIterator(max_page_to_iterate, page_factory)
    for page in page_iter:
        page.load()
    #temporary for testing
    while True:
        pass
