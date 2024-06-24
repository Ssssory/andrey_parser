"""Applicatinon entry point"""
import navigation.source as src
from navigation import page
from navigation.element import PageElementFactory
from driver import Driver
from config import initialize


if __name__ == '__main__':
    initialize(Driver())
    element_factory = PageElementFactory()
    page_factory = page.RentsListPageFactory(
            element_factory, src.PopupXPaths, page.RentsListPage)
    #move first parameter (page_numbers) to config file
    page_iter = page.PageIterator(2, page_factory)
    for page in page_iter:
        page.load()
    #temporary for testing
    while True:
        pass
