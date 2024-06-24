from bs4 import BeautifulSoup
from common.driver import inject_driver

class AdsListScrapper:
    def __init__(self, tag_name, css_class):
        self._parser_name = 'html.parser'
        self._tag_name = tag_name
        self._css_class = css_class

    @inject_driver()
    def get_ad_links(self, driver=None):
        links = []
        html_doc = driver.page_source
        soup = BeautifulSoup(html_doc, self._parser_name)

        def is_img(tag):
            return tag.name == self._tag_name and self._css_class in tag.attrs.get('class', [])

        for tag in soup.find_all(is_img):
            if link := tag.find_parent('a'):
                links.append(link.attrs.get('href', ''))

        return links
